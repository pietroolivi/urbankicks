<?php
require_once("bootstrap.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <title>UrbanKicks - <?php echo $templateParams["title"]; ?></title>
        <?php 
        if(isset($templateParams["js"])):
            foreach($templateParams["js"] as $script): 
        ?>
            <script src="<?php echo $script; ?>" defer></script>
        <?php 
            endforeach;
        endif;
        ?>
    </head>
    <body>
        <header>
            <h1>URBANKICKS</h1>
            <a class="cart-icon" href="cart.php"><img src="CSS/Images/Icons/cart.svg" alt="My shopping cart."></a>
        </header>
        
        <label for="hamburger-icon">Open/close the pop-up sidebar.</label>
        <label class="hamburger-menu"><input id="hamburger-icon" type="checkbox"></label>
        <aside class="hamburger-sidebar">
            <form>
                <div class="search">
                    <span class="search-icon"><img src="CSS/Images/Icons/search.svg" alt="Execute search."></span>
                    <label for="search">Please, type the words that will be searched among the product names and descriptions.</label>
                    <input id="search" class="search-input" type="search" placeholder="Search within the site">
                </div>
            </form>
            <fieldset>
                <legend>Please select gender:</legend>
                <input type="radio" id="man" name="gender" value="man" checked="checked"><label for="man">MAN</label>
                <input type="radio" id="woman" name="gender" value="woman"><label for="woman">WOMAN</label>
                <input type="radio" id="kids" name="gender" value="kids"><label for="kids">KIDS</label>
            </fieldset>
            <nav class="main-hamburger-nav">
                <ul>
                    <li><a href="home.php">VIEW ALL</a></li>
                    <li><a href="#">SNEAKERS</a></li>
                    <li><a href="#">SANDALS</a></li>
                    <li><a href="#">SLIDERS</a></li>
                    <li><a href="#">PROMO</a></li>
                    <li><a href="#">LATEST</a></li>
                    <li><a href="#">POPULAR</a></li>
                </ul>
            </nav>
            <nav class="secondary-hamburger-nav">
                <ul>
                    <li><a href="#">CONTACT US</a></li>
                    <li><a href="#">SHIPPING & RETURNS</a></li>
                </ul>
            </nav>
            <p>URBANKICKS</p>
        </aside>
        
        <label for="profile-icon">Open/close the pop-up user menu.</label>
        <label class="profile-menu"><input id="profile-icon" type="checkbox"></label>
        <aside class="profile-sidebar">
            <nav>
                <ul>
                    <?php if(isset($_SESSION['user_email'])): ?>
                        <li><a href="orders.php">MY ORDERS</a></li>
                        <li><a href="wishlist.php">WISHLIST</a></li>
                        <li><a href="notifications.php">NOTIFICATIONS</a></li>
                        <li><a href="account.php">SETTINGS</a></li>
                        <li><a href="logout.php">LOGOUT</a></li>
                    <?php else: ?>
                        <li><a href="login.php">LOGIN</a></li>
                        <li><a href="register.php">REGISTER</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <main>
            <?php require($templateParams["name"]); ?>
        </main>

        <footer>
            <nav>
                <a href="#"><img src="CSS/Images/Icons/anchor.svg" alt="Top of the page."></a>
            </nav>
            <nav>
                <ul>
                    <li><a href="about_us.html">About Us</a></li>
                    <li><a href="contact_us.html">Contact Us</a></li>
                    <li><a href="accessibility.html">Accessibility</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li class="social-media-link"><a href="https://www.facebook.com"><img src="CSS/Images/Icons/facebook.svg" alt="Facebook"></a></li>
                    <li class="social-media-link"><a href="https://www.instagram.com"><img src="CSS/Images/Icons/instagram.svg" alt="Instagram"></a></li>
                    <li class="social-media-link"><a href="https://www.whatsapp.com"><img src="CSS/Images/Icons/whatsapp.svg" alt="Whatsapp"></a></li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li><a href="terms_and_conditions.html">Terms & Conditions</a></li>
                    <li><a href="privacy_policy.html">Privacy Policy</a></li>
                </ul>
            </nav>
        </footer>
    </body>
</html>