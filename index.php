<?php
include 'Layout/header.php';
include 'Layout/menu.php';
?>


<div class="container mx-auto mt-10">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6">Bienvenido al sistema de dependencias</h1>
        <p>Seleccione una de las opciones en el menú para continuar.</p>

        <section>
            
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Distribución</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^3.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Chart Section -->
    <section id="chart" class="mb-8 p-6">
        <h2 class="text-xl font-bold mb-4">Gráfico de Distribución</h2>
        <div class="bg-white p-4 rounded shadow-md">
            <canvas id="myDoughnutChart"></canvas>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Ejemplo de datos para empresas (remplaza esto con datos reales desde una API o base de datos)
            const empresasData = [
                { id: 1, nombre: 'Empresa A', nit: '1234567890', correo: 'empresaA@example.com' },
                { id: 2, nombre: 'Empresa B', nit: '0987654321', correo: 'empresaB@example.com' }
            ];

            const dependenciasData = [
                { id: 1, nombre: 'Dependencia A', telefono: '123456789', direccion: 'Dirección A' },
                { id: 2, nombre: 'Dependencia B', telefono: '987654321', direccion: 'Dirección B' }
            ];

            const colaboradoresData = [
                { id: 1, nombres: 'John', apellidos: 'Doe', correo: 'john@example.com' },
                { id: 2, nombres: 'Jane', apellidos: 'Doe', correo: 'jane@example.com' }
            ];

            // Datos para el gráfico de dona
            const chartData = {
                labels: ['Empresas', 'Dependencias', 'Colaboradores'],
                datasets: [{
                    data: [empresasData.length, dependenciasData.length, colaboradoresData.length],
                    backgroundColor: ['#4CAF50', '#FF9800', '#03A9F4'],
                    hoverBackgroundColor: ['#66BB6A', '#FFB74D', '#29B6F6']
                }]
            };

            // Crear gráfico de dona
            const ctx = document.getElementById('myDoughnutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

</section>
    </div>
</div>

<?php
include 'Layout/footer.php';
?>