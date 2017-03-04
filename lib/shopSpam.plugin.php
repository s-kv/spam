<?php

class shopSpamPlugin extends shopPlugin
{
    public static function getDiscounts() {
        // Правила скидок
        $discount_groups = shopFlexdiscountHelper::getDiscounts();
        
        $select = array();
        
        // Выполняем перебор групп скидок
        foreach ($discount_groups as $group_id => $group) {
            $result_items = array();
            $rules = $group_id === 0 ? $group : $group['items'];
            foreach ($rules as $rule) {
                $coupon_cnt = 0;
                if ($rule['status'] && !$rule['deny']) {
                    if(!empty($rule['coupons'])){
                        foreach ($rule['coupons'] as $coupon_id) {
                            $coupon_plugin = new shopFlexdiscountCouponPluginModel();
                            $coupon = $coupon_plugin->getCoupon($coupon_id);
                            // Проверяем наличие купонов у правил скидок
                            if ($coupon && $coupon['type'] == 'generator') {
                                $rule['coupon_id'] = $coupon_id;
                                $coupon_cnt++;
                            }
                        }
                        
                        if ($coupon_cnt == 1) {
                            $data['value'] = $rule['id'];
                            $data['title'] = $rule['name'];
                            $data['description'] = $rule['description'];
                            $select[] = $data;
                        }
                    }
                }
            }
        }

        return $select;        
    }
}