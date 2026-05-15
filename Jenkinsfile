pipeline {
    agent any

    environment {
        APP_ENV = 'testing'
    }

    stages {

        stage('Checkout') {
            steps {
                echo 'Récupération du projet...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installation des dépendances Composer...'
                sh 'composer install'
            }
        }

        stage('Build') {
            steps {
                echo 'Build en cours...'
            }
        }

        stage('Syntax Check') {
            steps {
                echo 'Vérification syntaxe PHP...'
                sh 'php -l routes/web.php'
            }
        }

        stage('Tests') {
            steps {
                echo 'Exécution des tests Laravel...'
                sh 'php artisan test'
            }
        }

        stage('Clear Cache') {
            steps {
                echo 'Nettoyage cache Laravel...'
                sh 'php artisan optimize:clear'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Déploiement de l’application...'
            }
        }
    }

    post {

        success {
            echo 'Pipeline exécuté avec succès ✅'
        }

        failure {
            echo 'Le pipeline a échoué ❌'
        }

        always {
            echo 'Fin du pipeline Jenkins'
        }
    }
}