#!/bin/bash

# Script para ejecutar todas las pruebas y análisis de código
# Uso: ./run-tests.sh

echo "🚀 Iniciando análisis completo del proyecto Laravel..."

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir con colores
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 1. Verificar dependencias
print_status "Verificando dependencias..."

if ! command -v php &> /dev/null; then
    print_error "PHP no está instalado"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado"
    exit 1
fi

if ! command -v sonar-scanner &> /dev/null; then
    print_error "SonarScanner no está instalado"
    exit 1
fi

# 2. Instalar dependencias de composer
print_status "Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Configurar aplicación
print_status "Configurando aplicación..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

if [ ! -f .env.testing ]; then
    cp .env.example .env.testing
    sed -i '' 's/DB_DATABASE=laravel/DB_DATABASE=laravel_testing/' .env.testing
    sed -i '' 's/APP_ENV=local/APP_ENV=testing/' .env.testing
fi

# 4. Ejecutar migraciones
print_status "Ejecutando migraciones..."
php artisan migrate --force --env=testing

# 5. Ejecutar pruebas PHPUnit con cobertura
print_status "Ejecutando pruebas PHPUnit..."
mkdir -p tests/reports

# Ejecutar tests con cobertura XML para SonarQube
php artisan test --coverage-clover=coverage.xml --log-junit=tests/reports/phpunit.xml

if [ $? -eq 0 ]; then
    print_status "✅ Todas las pruebas PHPUnit pasaron correctamente"
else
    print_error "❌ Algunas pruebas PHPUnit fallaron"
    exit 1
fi

# 6. Verificar sintaxis PHP
print_status "Verificando sintaxis PHP..."
find app -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

# 7. Ejecutar análisis con SonarScanner
print_status "Ejecutando análisis de SonarQube..."

# Verificar si SonarQube está corriendo
if ! curl -s http://localhost:9000/api/system/status | grep -q "UP"; then
    print_warning "SonarQube no está corriendo. Iniciando con Docker..."
    docker-compose up -d sonarqube
    
    # Esperar a que SonarQube esté listo
    print_status "Esperando a que SonarQube esté listo..."
    timeout 120 bash -c 'until curl -s http://localhost:9000/api/system/status | grep -q "UP"; do sleep 5; done'
fi

# Ejecutar SonarScanner
sonar-scanner

if [ $? -eq 0 ]; then
    print_status "✅ Análisis de SonarQube completado"
    print_status "📊 Resultados disponibles en: http://localhost:9000"
else
    print_error "❌ Error en el análisis de SonarQube"
    exit 1
fi

# 8. Limpiar archivos temporales
print_status "Limpiando archivos temporales..."
php artisan migrate:rollback --force --env=testing

print_status "🎉 ¡Análisis completo terminado!"
print_status "📊 Revisa los resultados en SonarQube: http://localhost:9000"
print_status "📁 Cobertura HTML disponible en: coverage-html/index.html"
