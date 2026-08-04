<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add data - Simple CRUD Using PHP, MySQL and Bootstrap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header mb-4">
                    <h2>Add a new customer</h2>
                </div>
                <form action="../functions/add_process.php" method="post">
                    <div class="form-group">
                        <label>First name</label>
                        <input type="text" name="firstname" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Last name</label>
                        <input type="text" name="lastname" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required="">
                    </div>
                    <input type="submit" class="btn btn-primary" name="submit" value="save">
                </form>
            </div>
        </div>
    </div>
</body>
</html>