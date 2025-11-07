<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel Paciente</title>
    
</head>

<body>
    <h1>Bienvenido al Panel Paciente</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
            {{ __('Cerrar sesión') }}
        </flux:menu.item>
    </form>

    <a href="{{ url('/dashboard') }}"
        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
        Dashboard
    </a>
</body>

</html>
