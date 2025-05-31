#!/bin/bash

echo "🔍 Verificando configuración de testing..."

# Verificar conexión a BD de desarrollo
echo "📊 Verificando conexión a BD de desarrollo..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Conexión exitosa a BD desarrollo';"

# Verificar conexión a BD de testing
echo "🧪 Verificando conexión a BD de testing..."
php artisan tinker --execute="DB::connection('pgsql')->statement('SELECT 1'); echo 'Conexión exitosa a BD testing';" --env=testing

# Verificar que las tablas existen
echo "📋 Verificando tablas en BD de testing..."
php artisan tinker --execute="Schema::hasTable('municipalidads') ? print('✅ Tabla municipalidads existe') : print('❌ Tabla municipalidads NO existe');" --env=testing

# Ejecutar una prueba rápida
echo "⚡ Ejecutando prueba rápida..."
php artisan test --filter=MunicipalidadTest --stop-on-failure

echo "✅ Verificación completada"
