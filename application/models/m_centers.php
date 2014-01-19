<?php
class M_centers extends CI_Model {
    private $data;
private  $index,$formated;

    function __construct()
    {
        parent::__construct();
    }
    
     function getCenter($centerID){
       return $this->db->where('ID',$centerID)->get('centers')->row();
     }
    function getAll(){
        $this->db->order_by('parent');
        $categories = $this->db->get('centers');

        foreach($categories->result() as $category)
        {
            $organized_categories[$category->parent][] = $category;
        }

        return $organized_categories;
    }
    function getAllChildren($centerID){
        $all=self::getAll();
        $list=array();
        self::centerChildren($centerID,$all,$list);
        return $list;
    }
    function getAllParents($centerID){
        $center=self::getCenter($centerID);

        $list=array();
        while($center->parent!=null){
            $center=self::getCenter($center->parent);
            $list[]=$center;
        }
        return $list;

    }
    function getChildren($centerID){
        $this->data = array();
        $this->index=array();
        $this->formated=array();

        $query = $this->db->select("ID as id,name as label,lat,lng,parent")->get('centers')->result_array();
        $new = array();
        foreach ($query as $a){
            $new[$a['parent']][] = $a;
        }
        /*
         * Recursive top-down tree traversal example:
         * Indent and print child nodes
         */
        $this->index=$new;
//        print_r($this->data);
//        print_r($this->index);
       $this->formated= $this->display_child_nodes($new[0]);
//        foreach($resutlt as $row){
//            $formated[]=Array("label"=>$row['name'],"id"=>$row['ID'],"load_on_demand"=>true);
//        }
       return $this->formated;
//        print_r($this->index);
//        print_r($this->formated);

    }
    function display_child_nodes($parent)
    {


//        print_r($parent);
        $tree = array();
        foreach ($parent as $k=>$l){
//            print_r($l);
//            print_r("\n++++++++++++++\n");
//            print_r($parent);

//            print_r($tree);
//            print_r("\n++++++++++++++\n");
            if(isset($this->index[$l['id']])){
                $l['children'] = $this->display_child_nodes($this->index[$l['id']]);
//                print_r($l);
//                print_r("\n++++++++++++++\n");
            }


            $tree[] = $l;
        }
//
        return $tree;

    }
    function centerChildren($centerID,$all,&$Children=null){

//        print_r($all);

        if(isset($all[$centerID])){
        foreach($all[$centerID] as $child){
            $Children[]=$child;

            $this->centerChildren($child->ID,$all,$Children);

        }}





    }

	
}
?>
