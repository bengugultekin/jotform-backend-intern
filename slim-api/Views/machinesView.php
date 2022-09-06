<?php require __DIR__ . '/header.php'; ?>
<?require __DIR__ . '/../Controllers/ViewController.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/v1/machines">Machines <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/v1/executions">Executions <span class="sr-only">(current)</span></a>
      </li>
      
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<div style="width: 65%; margin:0 auto">
<br><br>
<h1 style="text-align: center;">Manage Machines</h1>
<br>
<table style=" text-align:center;" class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#id</th>
        <th scope="col">CONTAINER NAME</th>
        <th scope="col">CREATED AT</th>
        <th>MANAGE</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach ($machines as $machine){ ?>
          <?php 
          session_start();
          $id = $machine->id;
          $_SESSION['id'] = $id;?>
        <td> <?php print_r($machine->id);?> </td>
        <?php 
          $name = $machine->container_name;
          $_SESSION['name'] = $name;?>
        <td> <?php print_r($machine->container_name);?> </td>
        <?php 
          $created_at = $machine->created_at;
          $_SESSION['created_at'] = $created_at;?>
        <td> <?php print_r($machine->created_at);?> </td>
        <th><a style="color:black;" href="/v1/machine/<?php echo $id; ?>">Go To Machine</a></th>
        </tr>
	<?php } ?>
    </tbody>
  </table>
</div>

  
 
<?php require __DIR__ . '/footer.php' ?>