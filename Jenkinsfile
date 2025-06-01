pipeline {
    agent {
        docker {
            image 'turismo-backend-ci'
            args '-u root:root'
        }
    }

    environment {
        SONAR_HOST_URL = 'http://localhost:9000'
        SONAR_TOKEN = credentials('sonar-token')
        COMPOSER_PROCESS_TIMEOUT = '1800' // 30 minutos
    }

    stages {
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist --no-progress --no-suggest'
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                    ./vendor/bin/phpunit --coverage-clover=coverage/clover.xml --log-junit=coverage/junit.xml || true
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                sh '''
                    # Instalar sonar-scanner
                    wget https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
                    unzip sonar-scanner-cli-4.8.0.2856-linux.zip
                    export PATH=$PATH:$(pwd)/sonar-scanner-4.8.0.2856-linux/bin
                    
                    # Ejecutar análisis
                    sonar-scanner \
                        -Dsonar.projectKey=turismo-backend \
                        -Dsonar.sources=app \
                        -Dsonar.tests=tests \
                        -Dsonar.php.coverage.reportPaths=coverage/clover.xml \
                        -Dsonar.php.tests.reportPath=coverage/junit.xml \
                        -Dsonar.host.url=$SONAR_HOST_URL \
                        -Dsonar.login=$SONAR_TOKEN
                '''
            }
        }
    }

    post {
        always {
            cleanWs()
        }
    }
}