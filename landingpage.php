<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Super Store</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Concert+One&display=swap');
        *{
            margin: 0;
            padding: 0;
        }
        
        .background {
            width: 100%;
            position: relative;
            height: 100vh;
            /* background-image: url(supermarket-8577513_1280.jpg); */
            background-image: url(images/landingpageimage.jpg);
            background-size: cover;
            object-fit: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); 
            z-index: 1;
        }
        .content{
            color: white;
            font-size: 2rem;
            font-family: "Concert One", sans-serif;
            font-style: normal;
            display: flex;
            flex-direction: column;
            z-index: 1;
            justify-content: center;
            align-items: center;
            text-shadow: 2px 4px 6px rgba(231, 118, 118, 0.5);
        }
        .content button{
            margin-top: 1.5rem;
            width: 50%;
            height: 30px;
            background-color: rgb(77, 136, 246);
            border: 2px solid white;
            border-radius: 10px;
            color: white;
            font-weight: bolder;
            cursor: pointer;
        }
        .content button:hover{
            background-color: rgb(120, 165, 249);
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="content">
            <h1>NextGen Super Store</h1>
            <p>Miyapur,Hyderabad,Telangana</p>
            <button onclick="window.location.href='loginpage.php'">Login</button>
        </div>
    </div>
</body>
</html>
