<?php
include_once 'includes/header.php';
include_once 'includes/db.inc.php';
?>
<style>
    h1 {
        font-size: 20px;
        color: #05204A;
    }

    .main {
        height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #fff;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .main-img {
        width: 100%;
        height: 100%;
        /* object-fit: cover; */
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .main-info {
        margin-top: 150px;
        position: relative;
        z-index: 3;
        background-color: rgba(0, 0, 0, 0.56);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(184, 121, 12, 0.5);
    }

    .btn-custom {
        background-color: #ff9800;
        color: white;
        border: none;
    }

    .btn-custom:hover {
        background-color: #603b02ff;
    }

    .section {
        padding: 50px 0;
        text-align: center;
    }

    .navbar {
        background-color: #ffffffff;
        height: 90px;
        font-weight: 600;
    }

    .navbar-nav .nav-link {
        margin-left: 12px;
        border-radius: 8px;
        color: #05204A;
        background-color: rgb(255, 255, 255);
        border-bottom: #05204A solid 2px;
    }

    .navbar-nav .nav-link:hover {
        color: #ff9800;
        background-color: rgb(255, 255, 255);
        border-bottom: #e68900 solid 2px;
    }

    .card-img-top {
        height: 250px;
    }

    @media only screen and (max-width: 768px) {
        .navbar {
            height: 100px;
        }

        .nav-logo {
            margin-top: -10px;
        }

        .navbar-toggler-icon {
            background-color: #9a9494;
        }

        .navbar-nav .nav-item :hover {
            background-color: rgb(226, 221, 221);
        }

        .navbar-nav .nav-link {
            background-color: #9a9494;
            text-align: center;
            color: #fff;
            border-bottom: #fff solid 2px;
        }

        .display-4 {
            line-height: 1.5em;
        }

        .location {
            width: 100%;
            height: 350px;

        }

        #contact {
            margin-bottom: 25px;
        }
    }
</style>
<nav class="navbar navbar-expand-lg">
    <div class="container mt-3">
        <h1 class="nav-logo"><img src="/decorluxe/assets/images/logo-bg.png" alt="logo" style="height: 80px; "></h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="#location">Location</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="/decorluxe/presentation/login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid main">
    <img src="/decorluxe/assets/images/index-img.jpeg" alt="" class="main-img">
    <div class="main-info">
        <!-- <h2 class="display-4">Decorluxe Interiors, <br> -->
        <h2> Exclusive Collection of Home Decor</h2>
        <!-- <p class="lead">Service Provider For All Kind Of Auto Electrical System </p> -->
        <a href="#services" class="btn btn-custom btn-lg mt-3">View Services</a>
    </div>
</div>


<section id="about" class="section bg-light">
    <div class="container">
        <h2>About Us</h2>
        <p>We are a trusted garage providing high-quality vehicle repair and maintenance services.</p>
    </div>
</section>

<section id="services" class="section">
    <div class="container">
        <h2>Our Services</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img1.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Interior Works</h5>
                        <p class="card-text">Transform your space into a masterpiece with our elegant
                            and functional interior designs tailored to your lifestyle</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img2.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Wall Panels</h5>
                        <p class="card-text">Add texture and character to your walls with modern, durable
                            wall panels that bring depth and luxury to any room.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img3.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Bespoke TV Wall Unit</h5>
                        <p class="card-text">Make your entertainment area the highligh of your home with our
                            custom designed TV wall units that blend style and practicality.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img4.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Ceiling Works</h5>
                        <p class="card-text">From sleek false ceilings to artistic lighting layputs - We create ceilings
                            designs that elevate your home's ambiance.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img5.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Pantry Cupboards</h5>
                        <p class="card-text">Stylish, spacious, and built to last - our custom pantry cupboards bring
                            both beauty and efficiency to your kitchen.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="/decorluxe/assets/images/img6.jpeg" alt="Service Image">
                    <div class="card-body">
                        <h5 class="card-title">Aluminium Works</h5>
                        <p class="card-text">Experience durablility and elegance with our high-quality aluminium doors,
                            partitions, and windows designed for modern living.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
$sql = "
SELECT 
    p.project_id,
    hs.description,
    (
        SELECT pi.image_path 
        FROM project_images pi 
        WHERE pi.project_id = p.project_id 
        LIMIT 1
    ) AS image_path
FROM homepage_settings hs
JOIN projects p ON hs.ref_id = p.project_id
WHERE hs.type = 'project'
";

$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<section id="gallery" class="section bg-light">
    <div class="container">
        <h2>Gallery</h2>

        <div class="row">

            <?php while ($row = mysqli_fetch_assoc($res)): ?>

                <div class="col-md-4 mb-4">
                    <div class="card">

                        <?php
                        $img = !empty($row['image_path'])
                            ? "/decorluxe/uploads/projects/" . $row['image_path']
                            : "/decorluxe/assets/images/default.jpg";
                        ?>

                        <img class="card-img-top" src="<?= $img ?>" alt="Project">

                        <div class="card-body">
                            <!-- <h5 class="card-title">
                                <?= htmlspecialchars($row['project_name']) ?>
                            </h5> -->

                            <p class="card-text">
                                <?= htmlspecialchars($row['description']) ?>
                            </p>
                        </div>

                    </div>
                </div>

            <?php endwhile; ?>

        </div>
    </div>
</section>

<section id="location" class="section ">
    <div class="container">
        <h2>Our Location</h2>
        <p>181 A, Avissawella Rd, Wellampitiya.</p>
        <div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5880047104743!2d79.88895477531752!3d6.939741993060296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259b49c40efc1%3A0x39981dbdd55eb2b0!2s181%20Avissawella%20Rd%2C%20Wellampitiya!5e0!3m2!1sen!2slk!4v1767789276537!5m2!1sen!2slk" width="750" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<section id="contact" class="section bg-light">
    <div class="container">
        <h2>Contact Us</h2>
        <p class="text-lowercase"><strong class="text-capitalize">Facebook: </strong>www.facebook.com/decorluxesl | <strong class="text-capitalize">Email: </strong> decorluxesl@gmail.com | <strong>Phone:</strong> 077 89 22711 , 077 12 93424 | <strong>Tel/Fex:</strong> 011-2572296</p>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>