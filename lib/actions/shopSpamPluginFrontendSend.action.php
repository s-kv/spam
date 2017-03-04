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
            $coupm = new shopCouponModel();
            $coupon['code'] = shopCouponsEditorAction::generateCode();

            $coupon['limit'] = $settings['limit'];
            $coupon['type'] = '%';
            $coupon['value'] = min(max($settings['procent'], 0), 100);
            $coupon['value'] = (float) str_replace(',', '.', $coupon['value']);
            
            $date = new DateTime();
            $date->add(new DateInterval('P'.$settings['expire_days'].'D'));
            $coupon['expire_datetime'] = $date->format('Y-m-d H:i:s');
            $coupon['create_datetime'] = date('Y-m-d H:i:s');
            
            $id = $coupm->insert($coupon);
            
            $param['coupon_code'] = $coupon['code'];
            $param['limit'] = $coupon['limit'];
            $param['value'] = $coupon['value'];
            $param['expire_datetime'] = $coupon['expire_datetime'];

            $view = wa()->getView();
            $view->clearAllAssign();
            $view->assign($param);

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
        } catch (waDbException $e) {
            $this->wh_log('Doubled coupon code. Rollback');
        } catch (waException $e) {
            $this->wh_log('Something wrong... ' . $e.__toString());
        }            
    }
    private function unsubscribe($data){
        wh_log($data['email'] . ' just unsubscribed!');
    }
    private function cleaned($data){
        wh_log($data['email'] . ' was cleaned from your list!');
    }
    private function upemail($data){
        wh_log($data['old_email'] . ' changed their email address to '. $data['new_email']. '!');
    }
    private function profile($data){
        wh_log($data['email'] . ' updated their profile!');
    }
    
    public function execute()
    {
        //$my_key  = 'EnterAKey!';
        $this->wh_log('==================[ Incoming Request ]==================');

        $this->wh_log("Full _REQUEST dump:\n".print_r($_REQUEST,true)); 
        
        //$_POST['type'] = 'subscribe'; // для теста
        
        /*if ( !isset($_GET['key']) ){
            $this->wh_log('No security key specified, ignoring request'); 
        } elseif ($_GET['key'] != $my_key) {
            $this->wh_log('Security key specified, but not correct:');
            $this->wh_log("\t".'Wanted: "'.$my_key.'", but received "'.$_GET['key'].'"');
        } else {*/
  
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

