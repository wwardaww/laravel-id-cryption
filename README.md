# Laravel Id Cryption

[![Latest Stable Version](https://poser.pugx.org/wwardaww/laravel-id-cryption/version)](https://packagist.org/packages/wwardaww/laravel-id-cryption)
[![License](https://poser.pugx.org/wwardaww/laravel-id-cryption/license)](https://packagist.org/packages/wwardaww/laravel-id-cryption)

This Trait **Encrypt** Your Data *(from database or somewhere)* or, **Decrypt** Your data *(from client or somewhere)* and it doesn't effect your real data *(from data source)*

### Installing

`composer require wwardaww/laravel-id-cryption`

**Note** : APP_KEY **must not be empty** in your `.env` file.

##### In Your Model File
- Use Trait : 
```php
use Wwardaww\Encryptable;
        
        class YourModel extends Model
        {
        
            use Encryptable; 
 ```
 
- Add your **Encryptable** columns and **Hidden** functions :

```php
 protected $encryptable = [
        'id',
        'account_id',
        'profile_id',
        'user_id',
        'some_id'
 ];
    
 protected $hiddenFunctions = [
        'someDefaultFunc',
        'someDefaultFunc2',  
 ];
```

### Usage

##### Example Table on Your Data Source


|ID       |Name           |
|---------|---------------|
|5        |Ahmet Oğuz     |

##### Get Object Whit Encrypted Id
```php
$encryptedId = "eyJpdiI6IlpxVWtpMGt4dERZbkRcL3hXVTZLKzVRPT0iLCJ2YWx1ZSI6IkhUNzF3MEFsRW1cL2tcLzVTRlZ6QmVaZz09IiwibWFjIjoiZTI5M2JiZTRiNzA2NGVjMTIwNmJhNWZjNjA4YmRmY2NlNzIxYTA2MWM3YTI1ZjVlYzQyMWQ5MzIwZDBlYzQ1OSJ9"
$data = YourModel::decryptFind($encrptedId);
```

##### Get Encrypted Data 

```php
$data = YourModel::where('somewhere','data')->get()->toArray();
```
- **Result Will be** : 

```php
Array(
    [
        "id" => "eyJpdiI6IlpxVWtpMGt4dERZbkRcL3hXVTZLKzVRPT0iLCJ2YWx1ZSI6IkhUNzF3MEFsRW1cL2tcLzVTRlZ6QmVaZz09IiwibWFjIjoiZTI5M2JiZTRiNzA2NGVjMTIwNmJhNWZjNjA4YmRmY2NlNzIxYTA2MWM3YTI1ZjVlYzQyMWQ5MzIwZDBlYzQ1OSJ9",
        "name" => "Ahmet Oğuz"
    ]
)
```
- **If You don't Convert to Array, Result Will be** :
```php
 $data->id = 5,
 $data->name = "Ahmet Oğuz"
```

##### Disable Encryption Specific Function

- You Should Add Your Function to **$hiddenFunctions** in Your Model File
- When You Call Your Model in This Function , Result will be : 

###### Call
```php
public function someDefaultFunc2(Request $req){
    $data = YourModel::where('somewhere','data')->get()->toArray();
    ...
}

```
###### Result
```php
Array(
    [
        "id" => 5,
        "name" => "Ahmet Oğuz"
    ]
)
```
