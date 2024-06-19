<?php

function getCurrentPage()
{
  return isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'index';
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="DIGIMAX">
  <meta name="author" content="Digimax IT Solutions">
  <title>ACE</title>
  <link rel="shortcut icon" href="photos/logo.jpg">


  <!-- <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="dist/css/adminlte.min.css"> -->

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- BOOTSSTRAP 4 -->
  <!-- <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->


  <!-- Include Select2 CSS -->
  <!-- <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

  <!-- MDB-->
  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.0.0/mdb.min.css" rel="stylesheet" /> -->



  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

  <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="src/css/app.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">


  <style>
    /* Customize modal width */
    .modal-custom {
      max-width: 100%;
      /* Adjust this value as needed */
    }

    /* .form-control {
      border: 0;
      border-bottom-color: solid #fff;

      border-radius: 0px;
      box-shadow: none !important;
      outline: 0px !important;
      -webkit-appearance: none;
    }

    .form-floating:focus {
      border-bottom-color: 1px solid #fff;
      border: 0px;
      border-bottom: solid rgb(51, 45, 45);
      border-radius: 0px;
      box-shadow: 1px solid rgb(51, 45, 45) !important;
      outline: 0px !important;
      -webkit-appearance: none;
    }

    .form-control:focus {
      border: 0px;
      border-bottom: solid rgb(0, 149, 77);
      border-radius: 0px;
      outline: 0px !important;
      -webkit-appearance: none;
    }

    select option {
      background-color: rgb(0, 149, 77);
      color: #fff;
    } */
  </style>
</head>

<body>
  <div class="wrapper">