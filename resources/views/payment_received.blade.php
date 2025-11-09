<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Payment Received | Veins Skating Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Veins Skating Club" name="description" />
    <meta content="Aldhira" name="author" />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body data-bs-spy="scroll" data-bs-target="#topnav-menu">
    <div class="container justify-content-center" style="margin-top: 150px; margin-bottom: 150px;">
        <div class="text-center">
            <div class="title-heading">
                <h1 class="display-4 fw-bold text-white mb-3">{{ $message_header }}</h1>
                <p class="text-white-50 para-desc mb-4 mx-auto">{{ $message_body }}</p>
            </div>
            <button class="btn btn-primary btn-lg mt-4" onclick="window.location.href='{{ url('/portal') }}'">Back to Portal</button>
        </div>
    </div>
</body>

</html>
