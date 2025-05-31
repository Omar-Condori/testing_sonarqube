#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Iniciando pruebas Newman para Municipalidad CRUD...${NC}"

if ! command -v newman &> /dev/null
then
    echo -e "${RED}Newman no está instalado. Instálalo con: npm install -g newman${NC}"
    exit 1
fi

newman run postman/municipalidad-crud.postman_collection.json -e postman/municipalidad-crud.postman_environment.json

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Pruebas Newman completadas exitosamente.${NC}"
else
    echo -e "${RED}❌ Error en las pruebas Newman.${NC}"
    exit 1
fi
