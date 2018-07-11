<?php return [
    'plugin' => [
        'name' => 'Travel',
        'description' => 'Envíe sus ofertas de paquetes de viaje de una manera fácil, segura y rápida a través de correos electrónicos a su base de datos de usuarios.',
    ],
    'permission' => [
        'manage_plugin' => 'Mailing Travel',
        'manage_users' => 'Gestionar Usuarios',
        'manage_packages' => 'Gestionar paquetes',
        'manage_customer' => 'Gestionar clientes',
    ],
    'menu' => [
        'name' => 'Mailing Travel',
        'customers' => 'Clientes',
        'groups' => 'Grupos',
        'packages' => 'Paquetes',
    ],
    'group' => [
        'name' => 'Nombre del grupo',
        'list_name' => 'Grupos',
        'add_group' => 'Agregar grupo',
        'add_group_success' => 'Grupo agregado correctamente',
        'edit_group' => 'Editar grupo',
        'edit_group_success' => 'Grupo editado correctamente',
        'delete_group' => 'Grupo eliminado correctamente',
        'not_found_group' => 'Grupo eliminado o inexistente',
    ],
    'package' => [
        'name' => 'Nombre del paquete',
        'image' => 'Imagen/Flyer del paquete',
        'image_comment' => 'Formatos soportados: jpg, png',
        'list_name' => 'Paquetes',
        'add_package' => 'Crear paquete',
        'add_package_success' => 'Paquete agregado correctamente',
        'edit_package' => 'Editar paquete',
        'edit_package_success' => 'Paquete editado correctamente',
        'delete_package' => 'Paquete eliminado correctamente.',
        'not_found_package' => 'Paquete eliminado o inexistente.',
    ],
    'customer' => [
        'fullname' => 'Nombre y Apellido',
        'birthdate' => 'Fecha de Nacimiento',
        'ci' => 'Cédula de Identidad / DNI',
        'ci_expiration' => 'Fecha de expiración Cédula de Identidad / DNI',
        'passport' => 'Pasaporte',
        'passport_expiration' => 'Fecha de expiración de pasaporte',
        'ruc' => 'RUC',
        'business_name' => 'Razón Social',
        'city' => 'Nacionalidad',
        'group' => 'Grupo',
        'birthplace' => 'Lugar de Nacimiento',
        'additional_information' => 'Información Adicional',
        'list_name' => 'Clientes',
        'add_customer' => 'Agregar cliente',
        'add_customer_success' => 'Cliente agregado correctamente',
        'edit_customer' => 'Editar cliente',
        'edit_customer_success' => 'Cliente editado correctamente',
        'delete_customer' => 'Cliente eliminado correctamente.',
        'tab_emails' => 'Correos Electrónicos',
        'tab_phones' => 'Numeros Telefónicos',
        'tab_address' => 'Direcciones Físicas',
    ],
    'customerEmail' => [
        'list_name' => '',
        'email' => 'Email',
        'type' => 'Tipo',
    ],
    'customerAddress' => [
        'list_name' => '',
        'address' => 'Dirección',
        'type' => 'Tipo',
    ],
    'customerPhone' => [
        'list_name' => '',
        'phone' => 'Número de Teléfono y/o Celular',
        'type' => 'Tipo',
    ],
    'customerTypeOptions' => [
        'particular' => 'Particular',
        'labor' => 'Laboral',
        'other' => 'Otro',
    ],
    'packageSent' => [
        // butons
        'buttonSent' => 'Enviar un paquete',
        'buttonCreateSent' => 'A todos los clientes',
        'buttonCreateSentGroup' => 'A todos los clientes de un grupo específico',
        'buttonCreateSentUser' => 'A un cliente en específico',
        'buttonPreview' => 'Previsualizar',
        'buttonSentMassive' => 'Enviar',
        'buttonClose' => 'Cerrar',
        // messages
        '404Customer' => 'El cliente seleccionado no existe o ha sido eliminado',
        '404Package' => 'El paquete seleccionado no existe o ha sido eliminado',
        'sendAllSuccess' => 'El paquete seleccionado ha sido enviado correctamente.',
        // fields name
        'packageLabel' => 'Seleccionar paquete',
        'packagePrompt' => 'Por favor, seleccione un paquete',
        'groupLabel' => 'Seleccione un grupo',
        'customerLabel' => 'Seleccionar un cliente',
        'customerPrompt' => 'Por favor, seleccione un cliente',
        'subject' => 'Asunto del mensaje',
        // form
        'popupTitle' => 'Enviar paquete',
    ],
    'importExport' => [
        'titleImport' => 'Importar clientes',
        'titleExport' => 'Exportar clientes',
        'buttonImport' => 'Importar',
        'buttonExport' => 'Exportar'
    ],
    'mail' => [
        'activeAccount' => 'Este mensaje fue enviado por poseer una cuenta activa en nuestra plataforma.',
    ]
];