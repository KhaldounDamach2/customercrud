# 📊 CustomerCRUD UML Diagrams

This document contains comprehensive UML diagrams explaining the CustomerCRUD project architecture, flow, and components.

## 🏗️ System Architecture Diagram

```mermaid
graph TB
    subgraph "Client Layer"
        Browser[Web Browser]
        User[User Interface]
    end
    
    subgraph "Application Layer"
        Apache[Apache Web Server]
        PHP[PHP Application]
    end
    
    subgraph "Business Logic"
        CustomerController[Customer Controller]
        EmailService[Email Service]
    end
    
    subgraph "Data Layer"
        MySQL[(MySQL Database)]
        CustomerTable[(customers table)]
    end
    
    subgraph "External Services"
        SendGrid[SendGrid API]
        GitHub[GitHub Repository]
        GitHubActions[GitHub Actions CI/CD]
    end
    
    subgraph "Infrastructure"
        Docker[Docker Containers]
        AutoDeploy[Auto-Deploy System]
        SystemD[SystemD Services]
    end
    
    Browser --> Apache
    Apache --> PHP
    PHP --> CustomerController
    CustomerController --> MySQL
    MySQL --> CustomerTable
    CustomerController --> EmailService
    EmailService --> SendGrid
    
    GitHub --> GitHubActions
    GitHubActions --> AutoDeploy
    AutoDeploy --> SystemD
    SystemD --> Docker
    Docker --> Apache
```

## 🔄 Application Flow Diagram

```mermaid
sequenceDiagram
    participant U as User
    participant B as Browser
    participant A as Apache/PHP
    participant DB as MySQL
    participant E as SendGrid
    
    Note over U,E: Customer Creation Flow
    
    U->>B: Fill customer form
    B->>A: POST /create_customer.php
    A->>DB: INSERT customer data
    DB-->>A: Confirm insertion
    A->>E: Send welcome email
    E-->>A: Email sent confirmation
    A->>B: Redirect to success page
    B->>U: Show confirmation message
    
    Note over U,E: Customer Update Flow
    
    U->>B: Click edit customer
    B->>A: GET /update_customer.php?id=X
    A->>DB: SELECT customer by ID
    DB-->>A: Return customer data
    A->>B: Show pre-filled form
    U->>B: Modify and submit
    B->>A: POST updated data
    A->>DB: UPDATE customer record
    DB-->>A: Confirm update
    A->>B: Redirect to customer list
```

## 📁 Class Diagram

```mermaid
classDiagram
    class Customer {
        +int id
        +string name
        +string email
        +string phone
        +datetime created_at
        +datetime updated_at
        
        +create() bool
        +update() bool
        +delete() bool
        +findById(id) Customer
        +findAll() Customer[]
        +emailExists(email) bool
    }
    
    class SimpleEmailService {
        -string apiKey
        -string fromEmail
        -SendGrid sendgrid
        
        +__construct(apiKey, fromEmail)
        +sendWelcomeEmail(customer) bool
        +sendUpdateNotification(customer) bool
        -prepareMail(to, subject, content) Mail
    }
    
    class DatabaseConfig {
        +string host
        +string dbname
        +string username
        +string password
        +PDO connection
        
        +getConnection() PDO
        +testConnection() bool
    }
    
    Customer ||--o{ SimpleEmailService : uses
    Customer ||--o{ DatabaseConfig : connects
    
    note for Customer "Main business entity\nHandles CRUD operations"
    note for SimpleEmailService "Handles email notifications\nvia SendGrid API"
    note for DatabaseConfig "Database connection\nmanagement"
```

## 🚀 Deployment Flow Diagram

```mermaid
graph LR
    subgraph "Development"
        Dev[Developer]
        LocalCode[Local Code Changes]
        GitCommit[Git Commit]
    end
    
    subgraph "GitHub"
        Repo[GitHub Repository]
        Actions[GitHub Actions]
        Pipeline[CI/CD Pipeline]
    end
    
    subgraph "Production Server"
        AutoDeploy[Auto-Deploy Script]
        Timer[SystemD Timer]
        Deploy[Deploy Script]
        Docker[Docker Compose]
        Live[Live Application]
    end
    
    Dev --> LocalCode
    LocalCode --> GitCommit
    GitCommit --> Repo
    Repo --> Actions
    Actions --> Pipeline
    
    Pipeline -->|Success| AutoDeploy
    Pipeline -->|Failure| X[❌ No Deployment]
    
    Timer -->|Every 5min| AutoDeploy
    AutoDeploy -->|Checks GitHub API| Pipeline
    AutoDeploy -->|New Success| Deploy
    Deploy --> Docker
    Docker --> Live
```

## 🗃️ Database Schema Diagram

```mermaid
erDiagram
    CUSTOMERS {
        int id PK "Auto-increment primary key"
        varchar name "Customer full name"
        varchar email UK "Customer email (unique)"
        varchar phone "Customer phone number"
        timestamp created_at "Record creation time"
        timestamp updated_at "Last update time"
    }
    
    CUSTOMERS ||--o{ EMAIL_LOGS : "triggers"
    
    EMAIL_LOGS {
        int id PK
        int customer_id FK
        varchar email_type "welcome, update, etc"
        varchar status "sent, failed"
        timestamp sent_at
    }
```

## 🐳 Docker Architecture

```mermaid
graph TB
    subgraph "Docker Compose"
        subgraph "Web Container"
            PHP[PHP 8.1 + Apache]
            App[CustomerCRUD App]
            Composer[Composer Dependencies]
        end
        
        subgraph "Database Container"
            MySQL[MySQL 8.0]
            Volume[Persistent Volume]
            InitSQL[init.sql]
        end
        
        subgraph "Networks"
            Bridge[Bridge Network]
        end
    end
    
    subgraph "Host System"
        Port8081[Host Port 8081]
        Port3307[Host Port 3307]
    end
    
    PHP --> MySQL
    Volume --> MySQL
    InitSQL --> MySQL
    
    Port8081 --> PHP
    Port3307 --> MySQL
    
    Bridge --> PHP
    Bridge --> MySQL
```

## 🔄 Auto-Deployment State Diagram

```mermaid
stateDiagram-v2
    [*] --> Idle
    
    Idle --> CheckingGitHub : Timer Trigger (5min)
    CheckingGitHub --> NoNewCommits : Same commit
    CheckingGitHub --> CheckingPipeline : New commit found
    
    NoNewCommits --> Idle : Log "Already up to date"
    
    CheckingPipeline --> PipelineRunning : Status: in_progress
    CheckingPipeline --> PipelineSuccess : Status: success
    CheckingPipeline --> PipelineFailed : Status: failure
    CheckingPipeline --> PipelineNotFound : No pipeline yet
    
    PipelineRunning --> Idle : Wait for completion
    PipelineFailed --> Idle : Log error, skip deployment
    PipelineNotFound --> Idle : Wait for pipeline to start
    
    PipelineSuccess --> Deploying : Execute deploy.sh
    Deploying --> DeploymentSuccess : Docker containers updated
    Deploying --> DeploymentFailed : Deployment error
    
    DeploymentSuccess --> Idle : Log success, save commit
    DeploymentFailed --> RecoveryMode : Restart containers
    RecoveryMode --> Idle : Service restored
```

## 📊 Component Diagram

```mermaid
graph TB
    subgraph "Frontend Components"
        UI[User Interface]
        Forms[HTML Forms]
        Bootstrap[Bootstrap CSS]
    end
    
    subgraph "Backend Components"
        Router[PHP Router]
        Controllers[Controllers]
        Models[Models]
        Views[Views]
    end
    
    subgraph "Service Components"
        Email[Email Service]
        Database[Database Service]
        Validation[Validation Service]
    end
    
    subgraph "Infrastructure Components"
        WebServer[Apache Server]
        PHP_FPM[PHP-FPM]
        MySQL_DB[MySQL Database]
        Docker_Engine[Docker Engine]
    end
    
    subgraph "DevOps Components"
        CI_CD[GitHub Actions]
        AutoDeploy_Service[Auto-Deploy]
        Monitoring[System Monitoring]
    end
    
    UI --> Router
    Forms --> Controllers
    Controllers --> Models
    Models --> Database
    Controllers --> Email
    Controllers --> Views
    
    Router --> WebServer
    WebServer --> PHP_FPM
    Database --> MySQL_DB
    
    CI_CD --> AutoDeploy_Service
    AutoDeploy_Service --> Docker_Engine
    Docker_Engine --> WebServer
```

## 🔍 Use Case Diagram

```mermaid
graph LR
    subgraph "CustomerCRUD System"
        UC1[Create Customer]
        UC2[View Customers]
        UC3[Update Customer]
        UC4[Delete Customer]
        UC5[Send Email]
        UC6[Auto Deploy]
    end
    
    User --> UC1
    User --> UC2
    User --> UC3
    User --> UC4
    
    UC1 --> UC5
    UC3 --> UC5
    
    Developer --> UC6
    
    UC1 --> Database[(Database)]
    UC2 --> Database
    UC3 --> Database
    UC4 --> Database
    
    UC5 --> SendGrid[SendGrid API]
    UC6 --> GitHub[GitHub]
```

---

## 📝 Notes

- **Architecture**: Modern 3-tier architecture with Docker containerization
- **Security**: Environment-based configuration, input validation
- **Scalability**: Docker containers, stateless design
- **Reliability**: Auto-deployment with health checks, transaction safety
- **Maintainability**: Clean separation of concerns, comprehensive logging

## 🔗 References

- [README.md](README.md) - Project overview and setup
- [DEPLOYMENT.md](DEPLOYMENT.md) - Production deployment guide  
- [AUTO-DEPLOY-README.md](AUTO-DEPLOY-README.md) - Auto-deployment system