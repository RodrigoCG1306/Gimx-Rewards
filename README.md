# Gimx-Rewards

## Descripción del Proyecto

Gimx-Rewards es un proyecto que utiliza las tecnologías Tailwind, Livewire, npm y Composer para crear un sistema de recompensas para una aplicación de juego (GIMX). El objetivo es permitir a los usuarios ganar recompensas en el juego a medida que alcanzan ciertos hitos o logros.

## Dependencias

El proyecto Gimx-Rewards tiene las siguientes dependencias:

- **Tailwind**: Un framework CSS altamente personalizable que facilita la creación de interfaces modernas y atractivas. Puedes obtener más información sobre Tailwind en su [sitio web oficial](https://tailwindcss.com/).

- **Livewire**: Una biblioteca de front-end de Laravel que permite crear interfaces dinámicas utilizando PHP en el servidor y JavaScript en el cliente. Puedes obtener más información sobre Livewire en su [repositorio de GitHub](https://github.com/livewire/livewire).

- **npm**: Un administrador de paquetes para el entorno de desarrollo de JavaScript. Se utiliza para instalar y administrar las dependencias de JavaScript del proyecto. Puedes obtener más información sobre npm en su [sitio web oficial](https://www.npmjs.com/).

- **Composer**: Un administrador de paquetes para PHP. Se utiliza para instalar y administrar las dependencias de PHP del proyecto. Puedes obtener más información sobre Composer en su [sitio web oficial](https://getcomposer.org/).

- **Laravel Breeze**: Un paquete de Laravel que proporciona una configuración básica de autenticación y scaffolding de vistas. Puedes obtener más información sobre Laravel Breeze en su [repositorio de GitHub](https://github.com/laravel/breeze).

- **Spatie Permissions**: Un paquete de Laravel que proporciona una manera fácil de administrar y asignar permisos a los usuarios. Puedes obtener más información sobre Spatie Permissions en su [repositorio de GitHub](https://github.com/spatie/laravel-permission).

- **Laravel Excel**: Un paquete de Laravel que facilita la importación y exportación de datos a través de hojas de cálculo. Puedes obtener más información sobre Laravel Excel en su [repositorio de GitHub](https://github.com/Maatwebsite/Laravel-Excel).

- **Flowbite JS**: Una librería de JavaScript que proporciona componentes y estilos para la creación de interfaces web modernas. Puedes obtener más información sobre Flowbite JS en su [sitio web oficial](https://flowbite.com/).

- **Laravel ApexCharts**: Un paquete de Laravel que ofrece gráficos interactivos y visualmente atractivos utilizando ApexCharts. Puedes obtener más información sobre Laravel ApexCharts en su [repositorio de GitHub](https://github.com/ArielMejiaDev/laravel-apexcharts).

## Configuración del Proyecto

Sigue estos pasos para configurar el proyecto Gimx-Rewards en tu entorno de desarrollo:

1. Clona este repositorio en tu máquina local:

   ```
   git clone https://github.com/cristof-g/gimx-rewards.git
   ```

2. Accede al directorio del proyecto:

   ```
   cd gimx-rewards
   ```

3. Instala las dependencias de JavaScript utilizando npm:

   ```
   npm install
   ```

4. Instala las dependencias de PHP utilizando Composer:

   ```
   composer install
   ```

5. Copia el archivo de configuración de ejemplo `.env.example` y crea un archivo `.env`:

   ```
   cp .env.example .env
   ```

6. Abre el archivo `.env` y configura las variables de entorno según tu entorno de desarrollo. Asegúrate de establecer las siguientes variables:

   - `APP_NAME`: El nombre de tu aplicación.
   - `APP_URL`: La URL de tu aplicación.
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Los detalles de conexión a tu base de datos.

7. Genera una clave de aplicación utilizando el comando de Artisan:

   ```
   php artisan key:generate
   ```

8. Ejecuta las migraciones de la base de datos y los seeders para crear las tablas necesarias y poblar la base de datos con datos de ejemplo:

   ```
   php artisan migrate --seed
   ```

9. Ejecuta el comando de Artisan para generar una clave de cifrado:

   ```
   php artisan key:generate
   ```

## Ejecución del Proyecto

Una vez que hayas configurado el proyecto, sigue estos pasos para ejecutarlo:

1. Compila los assets CSS y JavaScript utilizando npm:

   ```
   npm run dev
   ```

   También puedes utilizar `npm run watch` para compilar los assets y observar los cambios en tiempo real durante el desarrollo.

2. Inicia el servidor web de desarrollo de Laravel:

   ```
   php artisan serve
   ```

   Esto iniciará el servidor de desarrollo en `http://localhost:8000`.

3. Accede a `http://localhost:8000` en tu navegador para ver la aplicación en funcionamiento.

¡Gracias por tu interés en el proyecto Gimx-Rewards! Si tienes alguna pregunta o necesitas ayuda, no dudes en comunicarte con nosotros.