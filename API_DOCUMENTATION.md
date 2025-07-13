# API Documentation - E-Prescription System

## Base URL
```
http://localhost:8000/api
```

## Authentication
Sistem menggunakan Laravel Sanctum untuk authentication. Semua request (kecuali login) memerlukan Bearer Token.

### Login
**POST** `/api/login`

**Body (JSON):**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "role": "admin"
        },
        "token": "1|abc123..."
    }
}
```

### Logout
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

## User Credentials

### Admin
```
Email: admin@example.com
Password: password
```

### Dokter
```
Email: dokter@example.com
Password: password
```

### Apoteker
```
Email: apoteker@example.com
Password: password
```

## Dashboard API

### Get Dashboard Statistics
**GET** `/api/dashboard`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "statistics": {
            "total_resep": 10,
            "resep_draft": 2,
            "resep_diajukan": 3,
            "resep_disetujui": 2,
            "resep_selesai": 2,
            "resep_ditolak": 1,
            "total_obatalkes": 50,
            "obatalkes_stok_habis": 5,
            "obatalkes_stok_rendah": 10,
            "total_signa": 20
        },
        "recent_prescriptions": [...]
    }
}
```

## Signa Management API

### Get All Signa
**GET** `/api/signa`

### Get Signa by ID
**GET** `/api/signa/{id}`

### Create Signa
**POST** `/api/signa`

**Body (JSON):**
```json
{
    "signa_nama": "3x1 sehari",
    "is_active": true
}
```

### Update Signa
**PUT** `/api/signa/{id}`

**Body (JSON):**
```json
{
    "signa_nama": "3x1 sehari setelah makan",
    "is_active": true
}
```

### Delete Signa
**DELETE** `/api/signa/{id}`

## Obatalkes Management API

### Get All Obatalkes
**GET** `/api/obatalkes`

### Get Obatalkes by ID
**GET** `/api/obatalkes/{id}`

### Create Obatalkes
**POST** `/api/obatalkes`

**Body (JSON):**
```json
{
    "obatalkes_nama": "Paracetamol 500mg",
    "stok": 100,
    "is_active": true
}
```

### Update Obatalkes
**PUT** `/api/obatalkes/{id}`

**Body (JSON):**
```json
{
    "obatalkes_nama": "Paracetamol 500mg",
    "stok": 95,
    "is_active": true
}
```

### Delete Obatalkes
**DELETE** `/api/obatalkes/{id}`

## Prescription Management API

### Get All Prescriptions
**GET** `/api/resep`

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 10)

### Get Prescription by ID
**GET** `/api/resep/{id}`

### Create Prescription
**POST** `/api/resep`

**Body (JSON):**
```json
{
    "nama_pasien": "John Doe",
    "tanggal_resep": "2024-01-15",
    "catatan": "Pasien alergi penicillin",
    "action": "submit",
    "items": [
        {
            "obatalkes_id": 1,
            "signa_id": 1,
            "qty": 10
        }
    ],
    "racikan": [
        {
            "nama_racikan": "Racikan obat penurun panas",
            "signa_id": 2,
            "items": [
                {
                    "obatalkes_id": 2,
                    "qty": 5
                },
                {
                    "obatalkes_id": 3,
                    "qty": 3
                }
            ]
        }
    ]
}
```

### Update Prescription
**PUT** `/api/resep/{id}`

**Body (JSON):** Same as Create

### Delete Prescription
**DELETE** `/api/resep/{id}`

## Prescription Actions API

### Approve Prescription
**POST** `/api/resep/{id}/approve`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Resep berhasil disetujui",
    "data": {
        "id": 1,
        "no_resep": "RES-2024-001",
        "status": "disetujui",
        "approved_by": 2,
        "approved_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

### Reject Prescription
**POST** `/api/resep/{id}/reject`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Resep berhasil ditolak",
    "data": {
        "id": 1,
        "no_resep": "RES-2024-001",
        "status": "ditolak",
        "approved_by": 2,
        "approved_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

### Receive Prescription
**POST** `/api/resep/{id}/receive`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Resep berhasil diterima dan diselesaikan",
    "data": {
        "id": 1,
        "no_resep": "RES-2024-001",
        "status": "selesai"
    }
}
```

## Utility API

### Get Available Obatalkes
**GET** `/api/obatalkes-available`

**Response:**
```json
[
    {
        "obatalkes_id": 1,
        "obatalkes_nama": "Paracetamol 500mg",
        "stok": 100,
        "is_active": true
    }
]
```

### Get Available Signa
**GET** `/api/signa-available`

**Response:**
```json
[
    {
        "signa_id": 1,
        "signa_nama": "3x1 sehari",
        "is_active": true
    }
]
```

## Error Responses

### Validation Error (400)
```json
{
    "success": false,
    "message": "The nama_pasien field is required."
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Email atau password salah"
}
```

### Forbidden (403)
```json
{
    "success": false,
    "message": "Anda tidak memiliki izin untuk membuat resep"
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "No query results for model [App\\Models\\Resep] 1"
}
```

## Postman Collection

### Setup Postman
1. Import collection ke Postman
2. Set environment variable `base_url` = `http://localhost:8000/api`
3. Set environment variable `token` = (token dari login response)

### Collection Structure
```
E-Prescription API
├── Authentication
│   ├── Login
│   └── Logout
├── Dashboard
│   └── Get Statistics
├── Signa Management
│   ├── Get All Signa
│   ├── Get Signa by ID
│   ├── Create Signa
│   ├── Update Signa
│   └── Delete Signa
├── Obatalkes Management
│   ├── Get All Obatalkes
│   ├── Get Obatalkes by ID
│   ├── Create Obatalkes
│   ├── Update Obatalkes
│   └── Delete Obatalkes
├── Prescription Management
│   ├── Get All Prescriptions
│   ├── Get Prescription by ID
│   ├── Create Prescription
│   ├── Update Prescription
│   └── Delete Prescription
├── Prescription Actions
│   ├── Approve Prescription
│   ├── Reject Prescription
│   └── Receive Prescription
└── Utility
    ├── Get Available Obatalkes
    └── Get Available Signa
```

### Testing Workflow
1. **Login** dengan salah satu user (admin/dokter/apoteker)
2. **Copy token** dari response
3. **Set Authorization Header** dengan `Bearer {token}`
4. **Test endpoints** sesuai role user

### Role-based Testing

#### Admin Testing
- Test semua endpoints
- Buat, edit, approve, reject resep
- Kelola master data

#### Dokter Testing
- Buat dan edit resep
- Approve/reject resep dari dokter lain
- Tidak bisa kelola master data

#### Apoteker Testing
- Hanya bisa lihat resep disetujui/selesai
- Hanya bisa receive resep
- Tidak bisa buat/edit/approve resep

## Notes
- Semua response menggunakan format JSON
- Pagination menggunakan Laravel's built-in pagination
- Error handling konsisten dengan format response
- Role-based access control diimplementasi di semua endpoints 