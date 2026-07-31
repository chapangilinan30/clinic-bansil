<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f7fb;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0 0 20px rgba(0,0,0,0.1);
            text-align:center;
            width:320px;
        }

        button{
            padding:12px 25px;
            border:none;
            border-radius:8px;
            background:#003366;
            color:white;
            cursor:pointer;
            font-size:16px;
            width:100%;
        }

        button:hover{
            background:#0055aa;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Logout Account</h2>
    <p>Are you sure you want to logout?</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            Logout Now
        </button>
    </form>

</div>

</body>
</html>