<?php

namespace app\models;

use Yii;
use app\models\OrderDetails;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property string $order_id
 * @property string $is_parent_order
 * @property string $parent_order_id
 * @property string $company_id
 * @property string $issuer_id
 * @property string $user_id
 * @property string $total
 * @property string $subtotal
 * @property string $discount
 * @property string $subtotal_discount
 * @property string $payment_surcharge
 * @property string $shipping_ids
 * @property string $shipping_cost
 * @property string $timestamp
 * @property string $status
 * @property string $notes
 * @property string $details
 * @property string $promotions
 * @property string $promotion_ids
 * @property string $firstname
 * @property string $lastname
 * @property string $company
 * @property string $b_firstname
 * @property string $b_lastname
 * @property string $b_address
 * @property string $b_address_2
 * @property string $b_city
 * @property string $b_county
 * @property string $b_state
 * @property string $b_country
 * @property string $b_zipcode
 * @property string $b_phone
 * @property string $s_firstname
 * @property string $s_lastname
 * @property string $s_address
 * @property string $s_address_2
 * @property string $s_city
 * @property string $s_county
 * @property string $s_state
 * @property string $s_country
 * @property string $s_zipcode
 * @property string $s_phone
 * @property string $s_address_type
 * @property string $phone
 * @property string $fax
 * @property string $url
 * @property string $email
 * @property integer $payment_id
 * @property string $tax_exempt
 * @property string $lang_code
 * @property resource $ip_address
 * @property integer $repaid
 * @property string $validation_code
 * @property integer $localization_id
 * @property string $profile_id
 * @property string $yml_order_id
 */
class Orders extends \yii\db\ActiveRecord
{
    public static $db;
    public $total_price;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return self::$db;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_order_id', 'company_id', 'issuer_id', 'user_id', 'timestamp', 'payment_id', 'repaid', 'localization_id', 'profile_id', 'yml_order_id'], 'integer'],
            [['total', 'subtotal', 'discount', 'subtotal_discount', 'payment_surcharge', 'shipping_cost'], 'number'],
            [['notes', 'details', 'promotions'], 'string'],
            [['localization_id'], 'required'],
            [['is_parent_order', 'status', 'tax_exempt'], 'string', 'max' => 1],
            [['shipping_ids', 'promotion_ids', 'company', 'b_address', 'b_address_2', 's_address', 's_address_2'], 'string', 'max' => 255],
            [['firstname', 'lastname', 'b_county', 'b_state', 'b_zipcode', 'b_phone', 's_county', 's_state', 's_zipcode', 's_phone', 's_address_type', 'phone', 'fax', 'url'], 'string', 'max' => 32],
            [['b_firstname', 'b_lastname', 's_firstname', 's_lastname', 'email'], 'string', 'max' => 128],
            [['b_city', 's_city'], 'string', 'max' => 64],
            [['b_country', 's_country', 'lang_code'], 'string', 'max' => 2],
            [['ip_address'], 'string', 'max' => 40],
            [['validation_code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'is_parent_order' => 'Is Parent Order',
            'parent_order_id' => 'Parent Order ID',
            'company_id' => 'Company ID',
            'issuer_id' => 'Issuer ID',
            'user_id' => 'User ID',
            'total' => 'Total',
            'subtotal' => 'Subtotal',
            'discount' => 'Discount',
            'subtotal_discount' => 'Subtotal Discount',
            'payment_surcharge' => 'Payment Surcharge',
            'shipping_ids' => 'Shipping Ids',
            'shipping_cost' => 'Shipping Cost',
            'timestamp' => 'Timestamp',
            'status' => 'Status',
            'notes' => 'Notes',
            'details' => 'Details',
            'promotions' => 'Promotions',
            'promotion_ids' => 'Promotion Ids',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'company' => 'Company',
            'b_firstname' => 'B Firstname',
            'b_lastname' => 'B Lastname',
            'b_address' => 'B Address',
            'b_address_2' => 'B Address 2',
            'b_city' => 'B City',
            'b_county' => 'B County',
            'b_state' => 'B State',
            'b_country' => 'B Country',
            'b_zipcode' => 'B Zipcode',
            'b_phone' => 'B Phone',
            's_firstname' => 'S Firstname',
            's_lastname' => 'S Lastname',
            's_address' => 'S Address',
            's_address_2' => 'S Address 2',
            's_city' => 'S City',
            's_county' => 'S County',
            's_state' => 'S State',
            's_country' => 'S Country',
            's_zipcode' => 'S Zipcode',
            's_phone' => 'S Phone',
            's_address_type' => 'S Address Type',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'url' => 'Url',
            'email' => 'Email',
            'payment_id' => 'Payment ID',
            'tax_exempt' => 'Tax Exempt',
            'lang_code' => 'Lang Code',
            'ip_address' => 'Ip Address',
            'repaid' => 'Repaid',
            'validation_code' => 'Validation Code',
            'localization_id' => 'Localization ID',
            'profile_id' => 'Profile ID',
            'yml_order_id' => 'Yml Order ID',
        ];
    }
    
    public static function getOrdersTotal($product_code)
    {
        $thisMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $nextMonth = mktime(0, 0, 0, date('m') + 1, 1, date('Y'));
        $sql = "SELECT SUM(cod.amount) AS total, SUM(cod.price * cod.amount) AS total_price FROM " . self::tableName() . " co
                LEFT JOIN " . OrderDetails::tableName() . " cod ON co.order_id = cod.order_id
                WHERE co.timestamp > " . $thisMonth . " AND co.timestamp < " . $nextMonth . " AND cod.product_code = '" . $product_code . "' AND co.status IN ('C', 'X', 'P')";
        
        return self::findBySql($sql)->one();
    }
}
