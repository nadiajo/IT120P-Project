<?php 
include('Php/functions.php');

$conn = db_connect();


// The amount of products to show on each page
$num_products_on_each_page = 8;
// The current page - in the URL, will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// Calculate the offset for the query
$offset = ($current_page - 1) * $num_products_on_each_page;

// Prepare the SQL statement to select products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $num_products_on_each_page);
$stmt->execute();
// Fetch the products from the database and return the result as an Array
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Get the total number of products
$total_products_res = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $total_products_res->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title>Home</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
         
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	      <!--nadia start-->
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/homes.css"> 
        <link rel="stylesheet" href="Style/products.css">
        <!--nadia end-->
      </head>
<body>
    <div class="navbar">
        
        <h1>Ivatan Store</h1>
        <div class="search-container">
        <form action="search.php" method="get">
    <img src="Image/search.png" alt="Search" class="search-icon">
    <input type="search" name="query" placeholder="Search" required>
</form>
</div>
        <?php require_once("Php/navigation.php"); ?>
    </div>
    <br>
    <div>
  <div class="carousel_image">
    <ul class="slides">
      <input type="radio" name="radio-buttons" id="img-1" checked />
      <li class="slide-container">
        <div class="slide-image">
          <img src="Image\carousel\img1.jpg">
        </div>
        <div class="carousel-controls">
          <label for="img-3" class="prev-slide">
            <span>&lsaquo;</span>
          </label>
          <label for="img-2" class="next-slide">
            <span>&rsaquo;</span>
          </label>
        </div>
      </li>
      <input type="radio" name="radio-buttons" id="img-2" />
      <li class="slide-container">
        <div class="slide-image">
          <img src="Image\carousel\img2.jpg">
        </div>
        <div class="carousel-controls">
          <label for="img-1" class="prev-slide">
            <span>&lsaquo;</span>
          </label>
          <label for="img-3" class="next-slide">
            <span>&rsaquo;</span>
          </label>
        </div>
      </li>
      <input type="radio" name="radio-buttons" id="img-3" />
      <li class="slide-container">
        <div class="slide-image">
          <img src="Image\carousel\img3.jpg">
        </div>
        <div class="carousel-controls">
          <label for="img-2" class="prev-slide">
            <span>&lsaquo;</span>
          </label>
          <label for="img-1" class="next-slide">
            <span>&rsaquo;</span>
          </label>
        </div>
      </li>
      <div class="carousel-dots">
        <label for="img-1" class="carousel-dot" id="img-dot-1"></label>
        <label for="img-2" class="carousel-dot" id="img-dot-2"></label>
        <label for="img-3" class="carousel-dot" id="img-dot-3"></label>
      </div>
    </ul>
  </div>
</div>

    <main>

    <!--Nadia start-->
    <div class="category">
        <div class="icon"></div>
        <p class= "categ">Categories</p>
    </div>
    <h2 style="font-size:30px">Browse by Category</h2>
    <div class="wrapper"> 
        <i id="left" class="fa-solid  fas fa-angle-left"></i> 
        <ul class="carousel"> 
            <li class="card"> 
                <div class="img"><img src= "Image/t-shirt.png" 
                                      alt="" draggable="false"> </div> 
                <h2> Shirt</h2> 
            <li class="card"> 
                <div class="img"><img src= "Image/magnet.png" 
                                      alt="" draggable="false"> </div> 
                <h2>Magnets</h2> 
            </li> 
            <li class="card"> 
                <div class="img"><img src= "Image/hat.png" 
                                      alt="" draggable="false"> </div> 
                <h2>hats</h2> 
            </li> 
            <li class="card"> 
                <div class="img"><img src= "Image/fork.png" 
                                      alt="" draggable="false"> </div> 
                <h2>Foods</h2> 
            </li> 
            <li class="card"> 
                <div class="img"><img src= "Image/shopping-basket.png" 
                                      alt="" draggable="false"> </div> 
                <h2>basket</h2> 
            </li> 
            <li class="card"> 
                <div class="img"><img src= "Image/wallet.png" 
                                      alt="" draggable="false"> </div> 
                <h2>Wallets</h2> 
            </li> 
            <li class="card"> 
                <div class="img"><img src= "Image/keychain.png" 
                                      alt="" draggable="false"> </div> 
                <h2 >Keychains</h2> 
            </li> 
        </ul> 
        <i id="right" class="fa-solid fas fa-angle-right"></i> 
    </div> 

   
    <br></br>
 
    <!--Nadia end-->
    
    <section class="product-list">
            <?php foreach ($products as $product) : ?>
                <!-- Check if user is admin to provide edit link, else provide view details link -->
                <?php if (isAdmin()) : ?>
                    <a href="edit_product.php?page=product&id=<?= htmlspecialchars($product['id']) ?>">
                    <?php else : ?>
                        <a href="product_details.php?page=product&id=<?= htmlspecialchars($product['id']) ?>">
                        <?php endif; ?>
                        <div class="product-item">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-desc">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p><?= htmlspecialchars($product['brand']) ?></p>
                                <?php $priceOptions = explode(',', $product['price']);?>
                                <span class="price">₱<?= htmlspecialchars($priceOptions[0]) ?></span>
                            </div>
                        </div>
                        </a>
                    <?php endforeach; ?>
        </section>
        <div class="buttons">
            <?php if ($current_page > 1) : ?>
                <a href="Products.php?p=<?= $current_page - 1 ?>" class="button">Prev</a>
            <?php endif; ?>
            <?php if ($total_products > ($current_page * $num_products_on_each_page)) : ?>
                <a href="Products.php?p=<?= $current_page + 1 ?>" class="button">Next</a>
            <?php endif; ?>
            <!-- Admin-only buttons -->
            <?php if (isAdmin()) : ?>
                <a href="add product.php" class="button">Add Item</a>
            <?php endif; ?>
        </div>
        <br>
             <!-- Emerson Section -->
    <section class="delivery-customer_service-moneyback" style="display: flex; justify-content: space-around; align-items: center;">
    <div class="delivery" style="text-align: center; margin: 0 20px;">
        <img src="Image/delivery.png" alt="Fast Delivery" width="250" height="250">
        <h5><b>FREE AND FAST DELIVERY</b></h5>
        <p>Free delivery for all orders over ₱3,000.00</p>
    </div>
    <div class="customer service" style="text-align: center; margin: 0 20px;">
        <img src="Image/customer service.png" alt="Fast Delivery" width="250" height="250">
        <h5><b>24/7 CUSTOMER SERVICE</b></h5>
        <p>Friendly 24/7 customer support</p>
    </div>
    <div class="money back" style="text-align: center; margin: 0 20px;">
        <img src="Image/guarantee.png" alt="Fast Delivery" width="250" height="250">
        <h5><b>MONEY BACK GUARANTEE</b></h5>
        <p>We return money within 30 days</p>
    </div>
</section>
<!-- Emerson Section -->
            </main>
    
    </body>
    <?= template_footer() ?>
</body>
</html>