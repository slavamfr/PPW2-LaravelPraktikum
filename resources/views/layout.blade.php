<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Belajar Model PPW2</title>
    <!-- Bootstrap 5 CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        @include('layouts.header') 
        @yield('content')          <!-- Only one yield for content -->
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/4hb7ie5lG4Oj+G4WJHOh/+GVJQZyLvF5c5X54N" crossorigin="anonymous"></script>
    
    <!-- Bootstrap 5 JS dan Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-I9jbLbrgLshFScLJozkLfUyP9rAQBRfeyRAqT3l5UPB7dOAm28m+hF+gjKfvFpQD" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $('.date').datepicker({
            format: 'yyyy-MM-dd',
            autoclose: true
        });
    </script>
</body>
</html>

