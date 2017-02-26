<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property string $product_id
 * @property string $product_code
 * @property string $product_type
 * @property string $status
 * @property string $company_id
 * @property string $list_price
 * @property integer $amount
 * @property string $weight
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string $shipping_freight
 * @property string $low_avail_limit
 * @property string $timestamp
 * @property string $updated_timestamp
 * @property string $usergroup_ids
 * @property string $is_edp
 * @property string $edp_shipping
 * @property string $unlimited_download
 * @property string $tracking
 * @property string $free_shipping
 * @property string $feature_comparison
 * @property string $zero_price_action
 * @property string $is_pbp
 * @property string $is_op
 * @property string $is_oper
 * @property string $is_returnable
 * @property string $return_period
 * @property string $avail_since
 * @property string $out_of_stock_actions
 * @property string $localization
 * @property integer $min_qty
 * @property integer $max_qty
 * @property integer $qty_step
 * @property integer $list_qty_count
 * @property string $tax_ids
 * @property string $age_verification
 * @property integer $age_limit
 * @property string $options_type
 * @property string $exceptions_type
 * @property string $details_layout
 * @property string $shipping_params
 * @property string $facebook_obj_type
 * @property string $yml_brand
 * @property string $yml_origin_country
 * @property string $yml_store
 * @property string $yml_pickup
 * @property string $yml_delivery
 * @property string $yml_adult
 * @property string $yml_cost
 * @property string $yml_export_yes
 * @property integer $yml_bid
 * @property integer $yml_cbid
 * @property string $yml_model
 * @property string $yml_sales_notes
 * @property string $yml_type_prefix
 * @property string $yml_market_category
 * @property string $yml_manufacturer_warranty
 * @property string $yml_seller_warranty
 * @property string $buy_now_url
 * @property string $cp_additinal_product_enabled
 * @property string $cp_additinal_product_items
 * @property integer $cp_delivery_date
 * @property integer $cp_number_of_columns
 * @property string $use_geo
 * @property string $external_id
 * @property string $update_1c
 * @property string $cp_allow_sh_rest
 * @property string $cp_allow_pm_rest
 * @property string $cp_allow_area_rest
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_mbs');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'amount', 'length', 'width', 'height', 'low_avail_limit', 'timestamp', 'updated_timestamp', 'return_period', 'avail_since', 'min_qty', 'max_qty', 'qty_step', 'list_qty_count', 'age_limit', 'yml_bid', 'yml_cbid', 'cp_delivery_date', 'cp_number_of_columns'], 'integer'],
            [['list_price', 'weight', 'shipping_freight', 'yml_cost'], 'number'],
            [['facebook_obj_type', 'yml_brand', 'yml_origin_country', 'yml_model', 'yml_sales_notes', 'yml_type_prefix', 'yml_market_category', 'yml_manufacturer_warranty', 'yml_seller_warranty', 'buy_now_url', 'cp_additinal_product_items'], 'required'],
            [['cp_additinal_product_items', 'use_geo'], 'string'],
            [['product_code'], 'string', 'max' => 32],
            [['product_type', 'status', 'is_edp', 'edp_shipping', 'unlimited_download', 'tracking', 'free_shipping', 'feature_comparison', 'zero_price_action', 'is_pbp', 'is_op', 'is_oper', 'is_returnable', 'out_of_stock_actions', 'age_verification', 'options_type', 'exceptions_type', 'yml_store', 'yml_pickup', 'yml_delivery', 'yml_adult', 'yml_export_yes', 'cp_additinal_product_enabled', 'update_1c', 'cp_allow_sh_rest', 'cp_allow_pm_rest', 'cp_allow_area_rest'], 'string', 'max' => 1],
            [['usergroup_ids', 'localization', 'tax_ids', 'shipping_params', 'yml_market_category', 'buy_now_url'], 'string', 'max' => 255],
            [['details_layout', 'yml_sales_notes'], 'string', 'max' => 50],
            [['facebook_obj_type', 'yml_origin_country'], 'string', 'max' => 64],
            [['yml_brand', 'yml_model'], 'string', 'max' => 96],
            [['yml_type_prefix'], 'string', 'max' => 55],
            [['yml_manufacturer_warranty', 'yml_seller_warranty'], 'string', 'max' => 20],
            [['external_id'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_type' => 'Product Type',
            'status' => 'Status',
            'company_id' => 'Company ID',
            'list_price' => 'List Price',
            'amount' => 'Amount',
            'weight' => 'Weight',
            'length' => 'Length',
            'width' => 'Width',
            'height' => 'Height',
            'shipping_freight' => 'Shipping Freight',
            'low_avail_limit' => 'Low Avail Limit',
            'timestamp' => 'Timestamp',
            'updated_timestamp' => 'Updated Timestamp',
            'usergroup_ids' => 'Usergroup Ids',
            'is_edp' => 'Is Edp',
            'edp_shipping' => 'Edp Shipping',
            'unlimited_download' => 'Unlimited Download',
            'tracking' => 'Tracking',
            'free_shipping' => 'Free Shipping',
            'feature_comparison' => 'Feature Comparison',
            'zero_price_action' => 'Zero Price Action',
            'is_pbp' => 'Is Pbp',
            'is_op' => 'Is Op',
            'is_oper' => 'Is Oper',
            'is_returnable' => 'Is Returnable',
            'return_period' => 'Return Period',
            'avail_since' => 'Avail Since',
            'out_of_stock_actions' => 'Out Of Stock Actions',
            'localization' => 'Localization',
            'min_qty' => 'Min Qty',
            'max_qty' => 'Max Qty',
            'qty_step' => 'Qty Step',
            'list_qty_count' => 'List Qty Count',
            'tax_ids' => 'Tax Ids',
            'age_verification' => 'Age Verification',
            'age_limit' => 'Age Limit',
            'options_type' => 'Options Type',
            'exceptions_type' => 'Exceptions Type',
            'details_layout' => 'Details Layout',
            'shipping_params' => 'Shipping Params',
            'facebook_obj_type' => 'Facebook Obj Type',
            'yml_brand' => 'Yml Brand',
            'yml_origin_country' => 'Yml Origin Country',
            'yml_store' => 'Yml Store',
            'yml_pickup' => 'Yml Pickup',
            'yml_delivery' => 'Yml Delivery',
            'yml_adult' => 'Yml Adult',
            'yml_cost' => 'Yml Cost',
            'yml_export_yes' => 'Yml Export Yes',
            'yml_bid' => 'Yml Bid',
            'yml_cbid' => 'Yml Cbid',
            'yml_model' => 'Yml Model',
            'yml_sales_notes' => 'Yml Sales Notes',
            'yml_type_prefix' => 'Yml Type Prefix',
            'yml_market_category' => 'Yml Market Category',
            'yml_manufacturer_warranty' => 'Yml Manufacturer Warranty',
            'yml_seller_warranty' => 'Yml Seller Warranty',
            'buy_now_url' => 'Buy Now Url',
            'cp_additinal_product_enabled' => 'Cp Additinal Product Enabled',
            'cp_additinal_product_items' => 'Cp Additinal Product Items',
            'cp_delivery_date' => 'Cp Delivery Date',
            'cp_number_of_columns' => 'Cp Number Of Columns',
            'use_geo' => 'Use Geo',
            'external_id' => 'External ID',
            'update_1c' => 'Update 1c',
            'cp_allow_sh_rest' => 'Cp Allow Sh Rest',
            'cp_allow_pm_rest' => 'Cp Allow Pm Rest',
            'cp_allow_area_rest' => 'Cp Allow Area Rest',
        ];
    }
    
    public static function getProducts()
    {
        $sql = 'SELECT product_id, product_code, amount FROM ' . self::tableName() . ' WHERE product_code != ""';
        return self::findBySql($sql)->all();
    }
}
