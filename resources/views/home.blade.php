<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Home | Veins Skating Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Veins Skating Club" name="description" />
    <meta content="Aldhira" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">

    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">

    @include('layouts.head-css')

</head>

<body data-bs-spy="scroll" data-bs-target="#topnav-menu" data-bs-offset="60">

    <nav class="navbar navbar-expand-lg navigation fixed-top sticky">
        <div class="container">
            <a class="navbar-logo" href="index">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="60"
                    class="logo logo-dark">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="60"
                    class="logo logo-light">
            </a>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav ms-auto" id="topnav-menu">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#team">Team</a>
                    </li>
                </ul>

                <div class="my-2 ms-lg-2">
                    <a href="/portal" class="btn btn-outline-success w-xs">Sign in</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- hero section start -->
    <section class="section hero-section bg-ico-hero" id="home">
        <div class="bg-overlay bg-primary"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="text-white-50">
                        <h1 class="text-white fw-semibold mb-3 hero-title">No Veins No Gain</h1>
                        <p class="font-size-14">We are dedicated to developing skating skills and potential through safe, fun, and communitfy-ocused training for all ages.</p>

                        <!-- <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="#" class="btn btn-success">Get Whitepaper</a>
                            <a href="#" class="btn btn-light">How it work</a>
                        </div> -->
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- hero section end -->

    <!-- currency price section start -->

    <!-- currency price section end -->

    <!-- about section start -->
    <section class="section pt-4 bg-white" id="about">
        <div class="container" style="max-width: 72%">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <div class="small-title">About us</div>
                        <h4>Tentang Veins</h4>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="text-muted">
                        <p>Selamat datang di Veins Skating Club (VSC), klub sepatu roda resmi yang terdaftar di 
                            PORSEROSI (Persatuan Olahraga Sepatu Roda Seluruh Indonesia) Pemprov. DKI Jakarta. 
                        </p>
                        <p class="mb-4">
                            Didirikan oleh sekelompok pengurus dan pelatih yang penuh semangat dan cinta terhadap olahraga sepatu roda, 
                            VSC hadir dengan satu tujuan utama: menciptakan wadah yang menyenangkan dan edukatif bagi 
                            anak-anak dan dewasa yang ingin belajar dan mengembangkan keterampilan sepatu roda mereka
                        </p>
                        
                        <div class="mt-lg-5">

                            <h4>Misi Kami</h4>
                            <p>
                                Di VSC, kami percaya bahwa olahraga sepatu roda bukan hanya tentang keterampilan fisik, 
                                tetapi juga tentang pembelajaran sosial dan pengembangan karakter. Misi kami adalah:
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 ms-auto">
                    <div class="mt-4 mt-lg-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-bitcoin h2 text-success"></i> -->
                                        </div>
                                        <h5>Pendidikan Dasar</h5>
                                        <p class="text-muted mb-0">Mengajarkan teknik dasar sepatu roda yang benar dan aman kepada anak-anak dan dewasa, 
                                            memastikan mereka memiliki fondasi yang kuat untuk berkembang lebih lanjut.</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Lingkungan Yang Menyenangkan</h5>
                                        <p class="text-muted mb-0">Menciptakan suasana 'FUN' di mana anak-anak dan dewasa dapat belajar dan 
                                            bermain dengan teman-teman sebaya mereka, 
                                            baik untuk tujuan hobi maupun prestasi.</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Pengembangan Sosial</h5>
                                        <p class="text-muted mb-0">Mendorong anak-anak untuk bersosialisasi dan berinteraksi, 
                                            membantu mereka mengurangi ketergantungan pada gadget 
                                            dan memperkuat hubungan sosial mereka.</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-muted">

                        <div class="mt-lg-5">
                            <h4>Visi Kami</h4>
                        </div>
                        <p>
                            Kami memiliki visi untuk menjadikan VSC sebagai klub sepatu roda terdepan yang tidak hanya menghasilkan atlet berprestasi, 
                        tetapi juga individu-individu yang percaya diri dan berkarakter. Kami ingin anak-anak dan dewasa merasakan kegembiraan 
                        dan manfaat dari olahraga sepatu roda, sambil mengembangkan disiplin, kerja keras, dan semangat sportifitas.
                        </p>
                        <h5>
                            Aktifitas Program Kami: 
                        </h5>
                    </div>
                </div>
                <div class="col-lg-12 ms-auto">
                    <div class="mt-4 mt-lg-0">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-bitcoin h2 text-success"></i> -->
                                        </div>
                                        <h5>Kelas Dasar</h5>
                                        <p class="text-muted mb-0">Untuk pemula yang ingin belajar teknik dasar sepatu roda.</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Kelas Lanjutan</h5>
                                        <p class="text-muted mb-0">Untuk mereka yang ingin meningkatkan keterampilan mereka dan berkompetisi.</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Kelas Dewasa</h5>
                                        <p class="text-muted mb-0">Menyediakan sesi latihan bagi orang dewasa, 
                                            termasuk orang tua yang ingin bermain sepatu roda bersama anak-anak mereka.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Latihan Rutin</h5>
                                        <p class="text-muted mb-0">Sesi latihan reguler yang dipimpin oleh pelatih berpengalaman.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- <div class="card border mt-lg-5"> -->
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <!-- <i class="mdi mdi-wallet-outline h2 text-success"></i> -->
                                        </div>
                                        <h5>Kompetisi dan Pertandingan</h5>
                                        <p class="text-muted mb-0">Kesempatan untuk berpartisipasi dalam kompetisi lokal dan nasional.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                    <div class="text-muted">

                        <div class="mt-lg-5">
                            <h4>Komunitas Kami</h4>
                        </div>
                        <p>
                        VSC bukan hanya tentang olahraga, tetapi juga tentang komunitas. Kami bangga dengan dukungan kuat dari orang tua 
                        dan anggota kami yang selalu hadir untuk mendukung anak-anak dan dewasa dalam setiap langkah perjalanan mereka. 
                        Kami mengundang Anda untuk menjadi bagian dari komunitas kami yang hangat dan mendukung.
                        </p>
                    </div>
            </div>
            <div class="col-lg-12">
                    <div class="text-muted">

                        <div class="mt-lg-5">
                            <h4>Bergabunglah Dengan Kami</h4>
                        </div>
                        <p>
                        Apakah Anda mencari tempat yang menyenangkan dan aman bagi Anda dan keluarga untuk belajar sepatu roda? 
                        VSC adalah pilihan yang tepat! Bergabunglah dengan kami dan saksikan keluarga Anda tumbuh dan berkembang 
                        dalam lingkungan yang positif dan penuh semangat. Ingat, "no VEINS no GAIN"
                        </p>
                    </div>
            </div>
            <!-- end row -->

            <hr class="my-5">

        </div>
        <!-- end container -->
    </section>
    <!-- about section end -->

    <!-- Features start -->
    <section class="section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <!-- <div class="small-title">Features</div> -->
                        <h4>Program Kelas di Veins Skating Club (VSC)</h4>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row align-items-center pt-4">
                <div class="col-md-6 col-sm-8">
                    <div>
                        <img src="{{ URL::asset('build/images/crypto/features-img/img-1.png') }}" alt=""
                            class="img-fluid mx-auto d-block">
                    </div>
                </div>
                <div class="col-md-5 ms-auto">
                    <div class="mt-4 mt-md-auto">
                        <div class="d-flex align-items-center mb-2">
                            <div class="features-number fw-semibold display-4 me-3">01</div>
                            <h4 class="mb-0">Jadwal dan Lokasi</h4>
                        </div>
                        <p class="text-muted">
                            <h5>Lokasi: Plaza Bendera Velodrome, Rawamangun </h5>
                            <ul>
                                <li>
                                    Jumat Sore : 16.00 - 18.00 WIB
                                </li>
                                <li>
                                    Sabtu Pagi: 07.30 - 09.30 WIB
                                </li>
                                <li>
                                    Sabtu Sore: 16.00 - 18.00 WIB
                                </li>
                                <li>
                                    Minggu Pagi: 07.30 - 09.30 WIB
                                </li>
                                <li>
                                    Minggu Sore: 16.00 - 18.00 WIB (termasuk Kelas Dewasa)
                                </li>
                            </ul>
                            <h5>Lokasi: JIRTA, Sunter </h5>
                                <ul>
                                    <li>
                                        Sabtu Malam: 19.00 - 21.00 WIB
                                    </li>
                                </ul>
                        </p>
                       
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row align-items-center mt-5 pt-md-5">
                <div class="col-md-5">
                    <div class="mt-4 mt-md-0">
                        <div class="d-flex align-items-center mb-2">
                            <div class="features-number fw-semibold display-4 me-3">02</div>
                            <h4 class="mb-0">Biaya</h4>
                        </div>
                        <p class="text-muted">
                        <ul>
                                <li>
                                    Pendaftaran : Rp 150.000 (termasuk kaos)
                                </li>
                                <li>
                                    Regular Coaching : <ul>
                                        <li>
                                            Rp 300.000/bulan (1x seminggu)
                                        </li>
                                        <li>
                                            Rp 400.000/bulan (2x seminggu)
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    Rp 100.000/kedatangan : 1x Sesi kedatangan (hanya di Velodrome, dapat untuk trial dan tanpa harus mendaftar)
                                </li>
                                <li>
                                    Private Coaching (by appointment) :
                                    <ul>
                                        <li>
                                            1 jam: Rp 150.000 / sesi 
                                        </li>
                                        <li>
                                            2 jam (one on one): Rp 250.000 / sesi 
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>
                <div class="col-md-6  col-sm-8 ms-md-auto">
                    <div class="mt-4 me-md-0">
                        <img src="{{ URL::asset('build/images/crypto/features-img/img-2.png') }}" alt=""
                            class="img-fluid mx-auto d-block">
                    </div>
                </div>

            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Features end -->

    <!-- Team start -->
    <section class="section" id="team">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <div class="small-title">Coach</div>
                        <h4>Meet our team</h4>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="col-lg-12">
                <div class="owl-carousel owl-theme events navs-carousel" id="team-carousel" dir="ltr">
                    <div class="item ">
                        <div class="card text-center team-box">
                            <div class="card-body">
                                <div>
                                    <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt=""
                                        class="rounded">
                                </div>

                                <div class="mt-3">
                                    <h5>Rizal Prasetyo, ST.ars (Adhe)</h5>
                                    <P class="text-muted mb-0">Coach</P>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex mb-0 team-social-links" id="tooltip-container">
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container" title="Facebook">
                                            <i class="mdi mdi-facebook"></i>
                                        </a>
                                    </div>
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container" title="Linkedin">
                                            <i class="mdi mdi-linkedin"></i>
                                        </a>
                                    </div>
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container" title="Google">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="card text-center team-box">
                            <div class="card-body">
                                <div>
                                    <img src="{{ URL::asset('build/images/users/avatar-8.jpg') }}" alt=""
                                        class="rounded">
                                </div>
                                <div class="mt-3">
                                    <h5>Lika Lunardi Ariestika (Coach Lika)</h5>
                                    <P class="text-muted mb-0">Head Coach</P>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex mb-0 team-social-links" id="tooltip-container3">
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container3" title="Facebook">
                                            <i class="mdi mdi-facebook"></i>
                                        </a>
                                    </div>
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container3" title="Linkedin">
                                            <i class="mdi mdi-linkedin"></i>
                                        </a>
                                    </div>
                                    <div class="flex-fill">
                                        <a href="#" data-bs-toggle="tooltip"
                                            data-bs-container="#tooltip-container3" title="Google">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Team end -->

    <!-- Footer start -->
    <footer class="landing-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="mb-4 mb-lg-0">
                        <h5 class="mb-3 footer-list-title">Company</h5>
                        <ul class="list-unstyled footer-list-menu">
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Features</a></li>
                            <li><a href="#">Team</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="mb-4 mb-lg-0">
                        <h5 class="mb-3 footer-list-title">Contacts</h5>
                        <ul class="list-unstyled footer-list-menu">
                            <li><a href="https://instagram.com/veins_skating_club_official">Instagram: @veins_skating_club_official</a></li>
                            <li><a href="#">WA : 081 1118 6222 (Admin#1 VSC)</a></li>
                            <li><a href="#">WA : 081 2185 29600 (Admin#2 Mbak Tantri)</a></li>
                        </ul>
                    </div>
                </div>

                <!-- <div class="col-lg-3 col-sm-6">
                    <div class="mb-4 mb-lg-0">
                        <h5 class="mb-3 footer-list-title">Latest News</h5>
                        <div class="blog-post">
                            <a href="#" class="post">
                                <div class="badge badge-soft-success font-size-11 mb-3">Cryptocurrency</div>
                                <h5 class="post-title">Donec pede justo aliquet nec</h5>
                                <p class="mb-0"><i class="bx bx-calendar me-1"></i> 04 Mar, 2020</p>
                            </a>
                            <a href="#" class="post">
                                <div class="badge badge-soft-success font-size-11 mb-3">Cryptocurrency</div>
                                <h5 class="post-title">In turpis, Pellentesque</h5>
                                <p class="mb-0"><i class="bx bx-calendar me-1"></i> 12 Mar, 2020</p>
                            </a>

                        </div>
                    </div>
                </div> -->
            </div>
            <!-- end row -->

            <hr class="footer-border my-2">

            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="60">
                    </div>

                    <p class="mb-2">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Veins. Design & Develop by Aldhira
                    </p>
                </div>

            </div>
        </div>
        <!-- end container -->
    </footer>
    <!-- Footer end -->

    @include('layouts.vendor-scripts')

    <script src="{{ URL::asset('build/libs/jquery.easing/jquery.easing.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ URL::asset('build/libs/jquery-countdown/jquery.countdown.min.js') }}"></script>

    <!-- owl.carousel js -->
    <script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>

    <!-- ICO landing init -->
    <script src="{{ URL::asset('build/js/pages/ico-landing.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

</body>

</html>
