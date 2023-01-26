<?
class Product_block_model extends BaseModel {

	private $main_table = 'product_block';

	public function __construct() {
        parent::__construct();
	}

	public function put($data = false)
    {
        if (empty($data)) {
            return false;
		}

        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
	}

	public function findOne($id = false)
    {
        if (empty($id)) {
            return false;
		}

        return $this->db->where('id', $id)->get($this->main_table)->row_array();
	}

	public function update($data = false, $id = false)
    {
        if (empty($data) || empty($id)) {
            return false;
        }

        return $this->db->where('id', $id)->update($this->main_table, $data);
    }

}
?>
