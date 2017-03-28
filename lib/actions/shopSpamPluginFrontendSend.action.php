<?php

class shopSpamPluginFrontendSendAction extends shopFrontendAction
{
    private function wh_log($msg){
        waLog::log($msg, 'shop/plugins/spam/spam.log');
    }

    private function subscribe($data){
        $this->wh_log($data['email'] . ' just subscribed!');

        // Формирование объекта письма с адресатом $to, отправителем $from, 
        // темой $subject и текстом $body
        
        $settings = wa('shop')->getPlugin('spam')->getSettings();
        
        try {
            $model = new shopSpamPluginModel();
            $sspm = $model->getByField('email', $data['email']);
            
            if(empty($sspm['coupon_code']))
            {
                $flexDiscountModel = new shopFlexdiscountPluginModel();
                $rule = $flexDiscountModel->getDiscount($settings['flex_discount']);

                if (sizeof($rule['coupons']['generators']) == 1) {
                    $generator = array_shift($rule['coupons']['generators']);

                    $rule['coupon'] = $generator;
                    $rule['coupon']['code'] = shopFlexdiscountPluginHelper::generateCoupon($generator['id']);

                    // запоминаем соответствие
                    $sspm['email'] = $data['email'];
                    $sspm['coupon_code'] = $rule['coupon']['code'];

                    $model->insert($sspm);

                    $view = wa()->getView();
                    $view->clearAllAssign();
                    $view->assign($rule);

                    $body = $view->fetch('string:'.$settings['letter']);

                    $mail_message = new waMailMessage($settings['subject'], $body);
                    // Указываем отправителя
                    $mail_message->setFrom($settings['from'], 'Робот Вебасист');
                    // Задаём получателя
                    if ($settings['is_test']) {
                        $this->wh_log('Test mode');
                        $mail_message->setTo($settings['to'], 'Новый пользователь');
                    } else {
                        $this->wh_log('Real mode');
                        $mail_message->setTo($data['email'], 'Новый пользователь');
                    }
                    // Отправка письма
                    $mail_message->send();

                    $this->wh_log('Success mailing');
                } else {
                    $this->wh_log('Subscribe again - skip mailing');
                }
            }                
        } catch (waDbException $e) {
            $this->wh_log('Doubled coupon code. Rollback');
        } catch (waException $e) {
            $this->wh_log('Something wrong... ' . $e.__toString());
        }            
    }
    private function unsubscribe($data){
        $this->wh_log($data['email'] . ' just unsubscribed!');
        
        $model = new shopSpamPluginModel();
        $sspm = $model->getByField('email', $data['email']);
        
        $this->wh_log($sspm['coupon_code'] . ' to delete!');
        
        if(!empty($sspm['coupon_code']))
        {
            //$model->deleteByField('email', $data['email']);
            $model = new shopFlexdiscountCouponPluginModel();
            $model->deleteByField('code', $sspm['coupon_code']);
        }
    }
    private function cleaned($data){
        $this->wh_log($data['email'] . ' was cleaned from your list!');
    }
    private function upemail($data){
        $this->wh_log($data['old_email'] . ' changed their email address to '. $data['new_email']. '!');
    }
    private function profile($data){
        $this->wh_log($data['email'] . ' updated their profile!');
    }
    
    public function execute()
    {
        $this->wh_log('==================[ Incoming Request ]==================');

        $this->wh_log("Full _REQUEST dump:\n".print_r($_REQUEST,true)); 
        
        // обработка запроса
        $this->wh_log('Processing a "'.$_POST['type'].'" request...');
        switch($_POST['type']){
            case 'subscribe'  : $this->subscribe($_POST['data']);   break;
            case 'unsubscribe': $this->unsubscribe($_POST['data']); break;
            case 'cleaned'    : $this->cleaned($_POST['data']);     break;
            case 'upemail'    : $this->upemail($_POST['data']);     break;
            case 'profile'    : $this->profile($_POST['data']);     break;
            default:
                $this->wh_log('Request type "'.$_POST['type'].'" unknown, ignoring.');
        }

        $this->wh_log('Finished processing request.');
    }
}

