<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <style>
        .img-container {
            background-image: url('https://res.cloudinary.com/dxfq3iotg/image/upload/v1556165136/switzerland-862870_1920.jpg');
            height: '320px';
            background-repeat: 'no-repeat';
            background-size: 'contain';
        }
    </style>
</head>

<body>
    <section class="bg-slate-100">
        <div class="bg-white mx-auto" style="width: '500px'">
            <div class="mt-16 bg-gray-900 text-center p-8 ob">
                <h2 class="text-3xl font-extrabold text-white">PRUEBA TECNICA MAIL</h2>
            </div>
            <div class="h-full text-white text-center pt-10 img-container">
                <h2 class="text-2xl font-bold">Bienvenido {{ $user->name }},</h2>
                <div class="pt-4">
                    <p>Diviértete y disfruta de mucho contenido por miles de usuarios.</p>
                </div>
            </div>
            <div class="" style="height: '100px'; padding: 5px;">
                <h2 class="text-2xl font-bold px-5">TU CUENTA ESTA ACTIVA</h2>
                <p>Ingresa con tu correo ({{$user->email}}) y contraseña registrada anteriormente</p>
            </div>
        </div>
    </section>
</body>

</html>
