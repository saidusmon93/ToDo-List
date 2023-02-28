<?php
require_once './class/config.php';
require_once './class/crud.php';
$crud = new Crud();

if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
    $task = $_POST['task'];
    $name = ['name' => $task];
    $crud->create('tasks', $name);
    header('Location: /');
    exit;
}

if (isset($_POST['id']) || isset($_POST['status'])) {
    $id = $_POST['id'];
    $status["status"] = isset($_POST['status']) ? 'completed' : "active";
    $crud->update('tasks', $status, "id=$id");
}

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $crud->delete('tasks', $id);
}


if (isset($_GET['show']) && $_GET['show'] == 'all') {
    $tasks = $crud->read('tasks');
} elseif (isset($_GET['show']) && $_GET['show'] == 'completed') {

    $tasks = $crud->read('tasks', 'status="completed"');
} else {

    $tasks = $crud->read('tasks', 'status="active"');
}
if (isset($_GET['show'])) {
    $all = $_GET['show'] == 'all';
    $active = $_GET['show'] == 'active';
    $completed = $_GET['show'] == 'completed';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ToDo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />

</head>

<body>

    <div class="container pt-3 bg-light">
        <div class="w-100 m-auto">
            <div class="add">
                <form class="row g-3" method="Post">
                    <div class="col-auto">
                        <input class="form-control" placeholder="ToDo" name="task" />
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success mb-3">
                            Add Todo
                        </button>
                    </div>
                </form>
            </div>
            <div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="?show=active" class="nav-link text-secondary <?php if ($active) echo 'active' ?>">
                            Active
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?show=all" class="nav-link text-secondary <?php if ($all) echo 'active' ?> ">
                            All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?show=completed" class="nav-link text-secondary <?php if ($completed) echo 'active' ?>">
                            Completed
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <?php if (empty($tasks)) : ?>
                    <div class="py-3">
                        <h5>Добавте задачу</h5>
                    </div>
                <?php else : ?>
                    <?php foreach ($tasks as $key => $val) :  ?>
                        <div class="row hover mt-2">
                            <b class="col-1"><?php echo $key + 1 ?></b>
                            <div class="col-9 border-end border-start">
                                <div class="text-wrap fw-semibold">
                                    <label class="d-block">
                                        <?php echo $val['name'] ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="row">
                                    <div class="col-6">
                                        <form method="post">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" onchange="mycheck(event)" type="checkbox" role="switch" <?php if ($val['status'] == 'completed') echo 'checked' ?> name="status">
                                                <input type="hidden" name="id" value="<?php echo $val['id'] ?>">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form method="post">
                                            <input type="hidden" value="<?php echo $val['id'] ?>" name="delete" />
                                            <button type="submit" class="btn-close bg-danger" aria-label="Close"></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function mycheck(event) {
            event.target.form.submit();

        }
    </script>
</body>

</html>