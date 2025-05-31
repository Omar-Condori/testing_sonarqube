pipeline {
    agent any

    tools {
        sonarQubeScanner 'SonarScanner'  // Nombre registrado en Jenkins > Global Tool Configuration
    }

    environment {
        SONARQUBE_SERVER = 'SonarQube_Local'  // Nombre configurado en Jenkins > Configure System
    }

    stages {
        stage('Install dependencies') {
            steps {
                echo 'Instalando dependencias...'
                sh 'composer install'
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Ejecutando pruebas...'
                sh './vendor/bin/phpunit --log-junit test-results.xml'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube_Local') {
                    def scannerHome = tool 'SonarScanner'
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
    }
}
