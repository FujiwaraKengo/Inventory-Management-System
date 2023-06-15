<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <style>
        .custom-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .table-scroll {
            max-height: 1px; /* Adjust the maximum height as needed */
            overflow-y: auto;
        }

        .btn-purple {
            color: #ffffff;
            background-color: #8a2be2; /* Purple color code */
            border-color: #8a2be2; /* Purple color code */
        }
        .btn-purple:hover {
            background-color: #800080; /* Darker shade of purple on hover */
            border-color: #800080; /* Darker shade of purple on hover */
        }

        .btn-fixed-width {
            width: 80px; /* Adjust the width as needed */
        }
        
        .custom-table-width {
            width: 30%; /* Adjust the width as per your requirements */
            margin: 0 auto; /* Center the table horizontally */
        }

        @media print {
            .no-print {
                display: none;
            }

            table {
                page-break-inside: auto;
            }

            table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

    </style>

    <title>Inventory Management System</title>
  </head>
  <body>
    <?php include ('navbar.php'); ?>

    <div class="py4">