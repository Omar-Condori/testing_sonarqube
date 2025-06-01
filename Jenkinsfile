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
                withSonarQubeEnv('SonarQube') {
                    sh '''
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
    }

    post {
        always {
            cleanWs()
        }
    }
}