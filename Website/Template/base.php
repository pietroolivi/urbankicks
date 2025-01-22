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
    <body class="universal-body">
        <header>
            <label for="hamburger-icon">Open/close the pop-up sidebar.</label>
            <label class="hamburger-menu"><input id="hamburger-icon" type="checkbox" onchange="shiftHamburgerSidebar()"></label>
            <h1><a href="index.php">URBANKICKS</a></h1>
            <label for="profile-icon">Open/close the pop-up user menu.</label>
            <label class="profile-menu"><img src="CSS/Images/Icons/user_profile.svg" alt=""><input id="profile-icon" type="checkbox" onchange="shiftProfileSidebar()"></label>
            <a class="cart-icon" href="cart.php"><img src="CSS/Images/Icons/cart.svg" alt="My shopping cart."></a>
        </header>
        <main>
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
                    <input type="radio" id="man" name="gender" value="man" checked><label for="man">MAN</label><input type="radio" id="woman" name="gender" value="woman"><label for="woman">WOMAN</label><input type="radio" id="kids" name="gender" value="kids"><label for="kids">KIDS</label>
                </fieldset>
                <nav class="main-hamburger-nav">
                    <ul>
                        <li><a href="home.php">VIEW ALL</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&type=sneakers'">SNEAKERS</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&type=sandals'">SANDALS</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&type=sliders'">SLIDERS</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&category=discounted'">PROMO</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&category=novelties'">LATEST</a></li>
                        <li><a href="#" onclick="window.location.href='home.php?genre=' 
                        + document.querySelector('input[name=\'gender\']:checked').value 
                        + '&category=popular'">POPULAR</a></li>
                    </ul>
                </nav>
                <nav class="secondary-hamburger-nav">
                    <ul>
                        <li><a href="contact_us.php">CONTACT US</a></li>
                        <li><a href="#">SHIPPING & RETURNS</a></li>
                    </ul>
                </nav>
                <p>URBANKICKS</p>
            </aside>
            <script>
                function shiftHamburgerSidebar() {/*
                    if (event.type == "change") {
                        console.log("-------------------------------------------------");
                        console.log("func called by onchange");
                        console.log(document.getElementById("hamburger-icon").checked);
                        console.log("-------------------------------------------------");
                    } else {
                        console.log("-------------------------------------------------");
                        console.log("func called by body's listener");
                        console.log(document.getElementById("hamburger-icon").checked);
                        console.log("-------------------------------------------------");
                    }*/
                    if (document.getElementById("hamburger-icon").checked) {
                        document.getElementsByClassName("hamburger-sidebar")[0].style.translate = 0;
                        disableScrollOnBG();
                        document.getElementsByClassName("hamburger-sidebar")[0].style.overflow = "auto";
                    } else {
                        document.getElementsByClassName("hamburger-sidebar")[0].style.translate = "-100%";
                        enableScrollOnBG();
                    }
                }

                function shiftProfileSidebar() {
                    if (event.currentTarget.checked) {
                        document.getElementsByClassName("profile-sidebar")[0].style.translate = 0;
                        disableScrollOnBG();
                        document.getElementsByClassName("hamburger-sidebar")[0].style.overflow = "auto";
                    } else {
                        document.getElementsByClassName("profile-sidebar")[0].style.translate = "0 -300%";
                        enableScrollOnBG();
                    }
                }

                function disableScrollOnBG() {
                    document.getElementsByClassName("universal-body")[0].style.overflow = "hidden";                    
                    document.getElementsByClassName("universal-body")[0].style.height = "100vh";
                    document.getElementsByClassName("universal-body")[0].style.width = "100%";
                    document.getElementsByClassName("universal-body")[0].style.position = "fixed";
                }

                function enableScrollOnBG() {
                    document.getElementsByClassName("universal-body")[0].style.overflow = "unset";
                    document.getElementsByClassName("universal-body")[0].style.position = "unset";
                }

                /* ***************************************************************************************************** */
                /* When we click on the hamburger-shaped label, we have the opposite problem, that is, we have two       */
                /* triggers. First, the click is intercepted by the <body> listener which, if the intention was to show  */
                /* the sidebar, does not make any changes and passes the baton to the <input> onchange event. If the     */
                /* menu was already open (and the intent of the click was therefore to close it), the flow first enters  */
                /* this if, calling the shiftHamburgerSidebar() function which hides the menu, then the <input> onchange */
                /*event is triggered which, however, does not read the newly updated value of the checkbox, and still    */
                /* sees it as "checked" so the sidebar reappears in a few moments, so quickly that it seems like it has  */
                /* always been there. So we call preventDefault() to avoid the second call to shiftHamburgerSidebar().   */
                /* ***************************************************************************************************** */
                document.body.addEventListener('click', function() {
                    let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("hamburger-sidebar")[0];
                    if (document.getElementsByClassName("hamburger-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
                        document.getElementById("hamburger-icon").checked = false;
                        shiftHamburgerSidebar();
                        event.preventDefault();
                    }
                });

                document.body.addEventListener('click', function() {
                    let myElementToCheckIfClicksAreInsideOf = document.getElementsByClassName("profile-sidebar")[0];
                    if (document.getElementsByClassName("profile-sidebar")[0].style.translate == "0px" && !myElementToCheckIfClicksAreInsideOf.contains(event.target)) {
                        document.getElementById("profile-icon").checked = false;
                        shiftProfileSidebar();
                        event.preventDefault();
                    }
                });
            </script>
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
            <?php require($templateParams["name"]); ?>            
        </main>
        <div id="fill-height"></div>
        <footer>
            <nav>
                <a href="#"><img src="CSS/Images/Icons/anchor.svg" alt="Top of the page."></a>
            </nav>
            <nav>
                <ul>
                    <li><a href="about_us.html">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
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