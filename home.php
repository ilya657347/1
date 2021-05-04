<div class="wrap">
	<h1></h1><br>
	<?php 
		if($_POST){
      $data = json_decode($_POST["data"]);
      foreach ($data["products"] as $key => $value) {
          /*$start = new WC_Product_Attribute();
          $start->set_id(0);
          $start->set_name("Начало");
          $start->set_options(["00.00.00"]);
          $start->set_position(1);
          $start->set_variation(true);
          $start->set_visible(true);
          $end = new WC_Product_Attribute();
          $end->set_id(0);
          $end->set_name("Конец");
          $end->set_options(["00.00.00"]);
          $end->set_position(2);
          $end->set_variation(true);
          $end->set_visible(true);
          $my_post->set_attributes(["Начало" => $start, "Конец" => $end]);
          

          $my_post->save();*/
          /*echo $my_post->get_attributes();
          $koostis = array_shift( wc_get_product_terms( $my_post->$id, 'Начало', array( 'fields' => 'names' ) ) );
          echo $koostis;*/
          //print_r($my_post);
              }
    }
    else{
?>
<style type="text/css">
  .time_input{
    background-color: transparent;
    border: none;
    border-bottom: solid black 1px;
  }
  .time_input:focus{
    border: none;
    border-bottom: solid black 1px;
  }
  .list{
    display: flex;
    flex-direction: column;
  }
  .item{
    background-color: #acacac;
    border: solid 1px white;
  }
  .item_header{
    display: flex;
    justify-content: space-between;
    padding:10px 20px;
  }
</style>
<?php
$args = array('taxonomy' => 'product_cat');
$categories = get_categories( $args );
 
echo "Категория для билетов: <select>";
foreach( $categories as $item_cat ) {
  echo "<option value=".$item_cat->term_id.' > '.$item_cat->name.'</ option>';
}
echo "</select>";
$args = array(
    'post_type' => 'product'/*,
    'tax_query' => array(
        array (
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => 'Билеты',
        )
    ),*/
);
//print_r($_POST);
$query = new WP_Query($args);
$items = $query->query($args);
$product = wc_get_products([]);
$selected_category_id=21;
echo "<div class='list'>";
foreach( $product as $my_post ){
  $categories="";
  $visible = true;
  foreach ( $my_post->get_category_ids() as $value) {
    $categories.="$value ";
    if($value==$selected_category_id){
      $visible=true;
    }
  }

	echo '<div data-categories="'.$categories.(!$visible?'" hidden':'"').' class="item">
          <div class="item_header">
            <div class="item_name">'
            .$my_post->get_name().
          ' </div>
            <div>
              <input class="time_input" value='. $my_post->get_attribute("Начало") .'>
              -<input class="time_input" value='. $my_post->get_attribute("Конец") .'>
              <input type="checkbox">
            </div>
          </div>
        </div>';
}
echo "</div>";
?>

<button>Сохранить</button>
<?php
	 }?>
</div>