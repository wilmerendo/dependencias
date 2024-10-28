<?php
include 'Layout/header.php';
include 'Layout/menu.php';
?>

<div class="container mx-auto mt-10">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Listado de Empresas</h1>
            <button id="crearEmpresa" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-400">Crear
                Empresa</button>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2">#</th>
                    <th class="py-2 px-4 border-b-2">Nombre Empresa</th>
                    <th class="py-2 px-4 border-b-2">NIT</th>
                    <th class="py-2 px-4 border-b-2">Correo</th>
                    <th class="py-2 px-4 border-b-2">Teléfono</th>
                    <th class="py-2 px-4 border-b-2">Representante Legal</th>
                    <th class="py-2 px-4 border-b-2">Estado</th>
                    <th class="py-2 px-4 border-b-2">Acciones</th>
                </tr>
            </thead>
            <tbody id="empresasTableBody">
                <!-- Table content will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Create/Edit Form -->
<div id="empresaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Crear Empresa</h3>
            <form id="empresaForm" class="mt-2 text-left">
                <input type="hidden" id="empresa_id" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_empresa">Nombre
                        Empresa</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nombre_empresa" name="nombre_empresa" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nit">NIT</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nit" name="nit" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="correo">Correo</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="correo" name="correo" type="email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono">Teléfono</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono" name="telefono" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="direccion">Dirección</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="direccion" name="direccion" type="text">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_representante_legal">Nombre
                        Representante Legal</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nombre_representante_legal" name="nombre_representante_legal" type="text">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2"
                        for="contacto_representante_legal">Contacto Representante Legal</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="contacto_representante_legal" name="contacto_representante_legal" type="text">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="correo_representante_legal">Correo
                        Representante Legal</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="correo_representante_legal" name="correo_representante_legal" type="email">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado">
                        <input type="checkbox" id="estado" name="estado" class="mr-2 leading-tight">
                        <span class="text-sm">Activo</span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <button id="submitEmpresa"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">Guardar</button>
                    <button id="closeModal"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        loadEmpresas();

        $('#crearEmpresa').click(function () {
            openModal('create');
        });

        $('#closeModal').click(function () {
            $('#empresaModal').addClass('hidden');
        });

        $('#empresaForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = 'Controllers/empresas.php';
            let method = $('#empresa_id').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.message, 'success');
                        $('#empresaModal').addClass('hidden');
                        loadEmpresas();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al procesar la solicitud', 'error');
                }
            });
        });

        function loadEmpresas() {
            $.ajax({
                url: 'Controllers/empresas.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let tableBody = '';
                        response.data.forEach(function (empresa) {
                            tableBody += `
                            <tr>
                                <td class="py-2 px-4 border-b">${empresa.id}</td>
                                <td class="py-2 px-4 border-b">${empresa.nombre_empresa}</td>
                                <td class="py-2 px-4 border-b">${empresa.nit}</td>
                                <td class="py-2 px-4 border-b">${empresa.correo}</td>
                                <td  class="py-2 px-4 border-b">${empresa.telefono}</td>
                                <td class="py-2 px-4 border-b">${empresa.nombre_representante_legal}</td>
                                <td class="py-2 px-4 border-b">${empresa.estado == 1 ? 'Activo' : 'Inactivo'}</td>
                                <td class="py-2 px-4 border-b">
                                    <button class="editEmpresa bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-400" data-id="${empresa.id}">Editar</button>
                                    <button class="deleteEmpresa bg-red-500 text-white px-2 py-1 rounded hover:bg-red-400" data-id="${empresa.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                        });
                        $('#empresasTableBody').html(tableBody);
                        bindEditDeleteEvents();
                    } else {
                        Swal.fire('Error', 'No se pudieron cargar las empresas', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al cargar las empresas', 'error');
                }
            });
        }

        function bindEditDeleteEvents() {
            $('.editEmpresa').click(function () {
                let id = $(this).data('id');
                openModal('edit', id);
            });

            $('.deleteEmpresa').click(function () {
                let id = $(this).data('id');
                deleteEmpresa(id);
            });
        }

        function openModal(action, id = null) {
            $('#modalTitle').text(action === 'create' ? 'Crear Empresa' : 'Editar Empresa');
            $('#empresaForm')[0].reset();
            $('#empresa_id').val(id);

            if (action === 'edit') {
                $.ajax({
                    url: 'Controllers/empresas.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            let empresa = response.data;
                            $('#empresa_id').val(empresa.id);
                            $('#nombre_empresa').val(empresa.nombre_empresa);
                            $('#nit').val(empresa.nit);
                            $('#correo').val(empresa.correo);
                            $('#telefono').val(empresa.telefono);
                            $('#direccion').val(empresa.direccion);
                            $('#nombre_representante_legal').val(empresa.nombre_representante_legal);
                            $('#contacto_representante_legal').val(empresa.contacto_representante_legal);
                            $('#correo_representante_legal').val(empresa.correo_representante_legal);
                            $('#estado').prop('checked', empresa.estado == 1);
                        } else {
                            Swal.fire('Error', 'No se pudo cargar la información de la empresa', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Hubo un problema al cargar la información de la empresa', 'error');
                    }
                });
            }

            $('#empresaModal').removeClass('hidden');
        }

        function deleteEmpresa(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'Controllers/empresas.php',
                        method: 'DELETE',
                        data: JSON.stringify({ id: id }),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Eliminado', response.message, 'success');
                                loadEmpresas();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Hubo un problema al eliminar la empresa', 'error');
                        }
                    });
                }
            });
        }
    });
</script>

<?php
include 'Layout/footer.php';
?>