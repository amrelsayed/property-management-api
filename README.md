# Property Management REST API

Basic CRUD operations written in Symfony 7

## API Reference

#### List properties

```http
  GET /api/properties
```

| Parameter | Type      | Description                                         |
| :-------- | :-------- | :-------------------------------------------------- |
| `page`    | `integer` | Page number for pagination (default: 1)             |
| `limit`   | `integer` | Number of items per page (default: 10)              |
| `status`  | `string`  | Filter properties by status (e.g., available, sold) |

#### Get Property

```http
  GET /api/properties/{id}
```

| Parameter | Type      | Description                       |
| :-------- | :-------- | :-------------------------------- |
| `id`      | `integer` | **Required**. Id of item to fetch |

#### Create Property

```http
  POST /api/properties
```

| Parameter     | Type      | Description  |
| :------------ | :-------- | :----------- |
| `title`       | `string`  | **Required** |
| `description` | `string`  | **Required** |
| `price`       | `decimal` | **Required** |
| `location`    | `string`  | **Required** |

#### Update Property

```http
  PUT /api/properties
```

| Parameter     | Type      | Description  |
| :------------ | :-------- | :----------- |
| `title`       | `string`  | **Required** |
| `description` | `string`  | **Required** |
| `price`       | `decimal` | **Required** |
| `location`    | `string`  | **Required** |

#### Delete Property

```http
  DELETE /api/properties/{id}
```

| Parameter | Type      | Description                        |
| :-------- | :-------- | :--------------------------------- |
| `id`      | `integer` | **Required**. Id of item to delete |

#### Update Property Status

```http
  PUT /api/properties/{id}/status
```

| Parameter | Type      | Description                              |
| :-------- | :-------- | :--------------------------------------- |
| `id`      | `integer` | **Required**. Id of item to update       |
| `status`  | `string`  | **Required**. New status of the property |
