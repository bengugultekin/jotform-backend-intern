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
<br><br>
<h1 style="text-align: center;">Execution History</h1>
<br>
<div style="width: 65%; margin:0 auto">
<table style=" text-align:center;" class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#id</th>
        <th scope="col">Container ID</th>
        <th scope="col">EXEC COMMAND</th>
        <th scope="col">EXEC RESPONSE</th>
        <th scope="col">EXEC TIME</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach ($executions as $execution){ ?>
        <td> <?php print_r($execution->id);?> </td>
        <td> <?php print_r($execution->container_id);?> </td>
        <td> <?php print_r($execution->exec_command);?> </td>
        <td> <?php print_r($execution->exec_response);?> </td>
        <td> <?php print_r($execution->exec_time);?> </td>
      </tr>
	<?php } ?>
    </tbody>
  </table>
</div>

  
 
<?php require __DIR__ . '/footer.php' ?>