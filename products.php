<?php
include_once("./header.php");
include_once("./config.php");
//print_r($_SESSION);
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
}
if (isset($_GET["id"])) {
	if (isset($_GET['action']) and $_GET['action'] == "add") {
		$broken = false;
		if (count($_SESSION['cart']) > 0) {
			foreach ($_SESSION['cart'] as $i => $v) {
				if ($v['id'] == $_GET['id']) {
					$_SESSION['cart'][$i]['qty']++;
					$broken = true;
					break;
				}
			}
			if (!$broken) {
				array_push($_SESSION["cart"], array("id" => $_GET["id"], "qty" => 1));
				//echo $_GET["id"]."~~~".$v['id'];
			}
		} else {
			array_push($_SESSION["cart"], array("id" => $_GET["id"], "qty" => 1));
			//echo $_GET["id"]."~~~".$v['id'];
		}
	} elseif(isset($_GET['action']) and $_GET['action'] == "del") {
		foreach($_SESSION['cart'] as $i=>$v){
			if($v["id"]==$_GET['id']){
				array_splice($_SESSION['cart'],$i,1);
			}
		}
	}
}

function showProducts()
{
	global $PRODUCTS;
	foreach ($PRODUCTS as $p) {
		echo '<div id="' . $p['id'] . '" class="product">
				<img src="./images/' . $p['image'] . '">
				<h3 class="title"><a href="#">Product ' . $p['id'] . '</a></h3>
				<span>Price: $' . $p["price"] . '</span>
				<a class="add-to-cart" href="./products.php?id=' . $p["id"] . '&action=add">Add To Cart</a>
			</div>';
	}
}
function getProd($id)
{
	global $PRODUCTS;
	foreach ($PRODUCTS as $v) {
		if ($v["id"] == $id) {
			return $v;
		}
	}
	return -99999;
}
function showCart()
{
	$totalPrice = 0;
	foreach ($_SESSION['cart'] as $v) {
		$prod = getProd($v["id"]);
		echo "<tr><td>Product " . $prod['id'] . "</td><td>" . $prod['price'] . "</td><td>" . $v['qty'] . "</td><td>" . $prod['price'] * $v["qty"] . "</td><td><a href='./products.php?id=" . $prod["id"] . "&action=del'>Remove</a></td></tr>";
		$totalPrice += $v["qty"] * $prod["price"];
	}
	return $totalPrice;
}
?>
<div id="main">
	<div id="products">
		<? showProducts(); ?>
	</div>
	<div id="cart">
		<?
		echo "<table><tr><th>Name</th><th>Price</th><th>Quantity</th><th>TOTAL</th></tr>";
		$p = showCart();
		echo "</table>";
		echo "<h3>Grand Total:$p</h3>";
		?>
	</div>
</div>
<?php include_once("footer.php"); ?>