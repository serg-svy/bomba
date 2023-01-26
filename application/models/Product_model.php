<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends BaseModel
{
    protected $tblname = 'product';

    public function isProductsPreOrder($product_ids)
    {
        $no_preorder_partners = [ 7 ];
        $result = [];

        if(count($product_ids) > 0) {
            $data =  $this->db
                ->select(['product_id','preorder','partner_id'])
                ->where_in("product_id", $product_ids)
                ->get("product_stock")
                ->result();

            foreach ($data as $item) {
                if(!isset($result[$item->product_id])) {
                    $result[$item->product_id] = 1;
                }

                if($result[$item->product_id] == 0) continue;

                if(in_array($item->partner_id, $no_preorder_partners)) {
                    $result[$item->product_id] = 0;
                    continue;
                }

                if((int) $item->preorder === 0 ) {
                    $result[$item->product_id] = 0;
                }
            }
        }

        return $result;
    }
}
