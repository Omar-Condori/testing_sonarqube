pipeline {
    agent {
        docker {
            image 'composer:2.6'
            args '-u root:root'
        }
    }

    environment {
        SONAR_HOST_URL = 'http://localhost:9000'
        SONAR_TOKEN = credentials('sonar-token')
    }

    stages {
        stage('Install Dependencies') {
            steps {
                sh 'composer install'
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                    ./vendor/bin/phpunit --coverage-clover=coverage/clover.xml --log-junit=coverage/junit.xml
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
                            -Dsonar.php.tests.reportPath=coverage/junit.xml
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