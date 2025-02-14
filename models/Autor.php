<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use MongoDB\BSON\ObjectId;

class Autor extends ActiveRecord
{
    public static function collectionName()
    {
        // Nombre de la colección en MongoDB
        return 'autores';
    }

    public function attributes()
    {
        // Atributos de la colección
        return ['_id', 'nombre_completo', 'fecha_nacimiento', 'libros_escritos'];
    }

    public function rules()
    {
        // Reglas de validación
        return [
            [['nombre_completo', 'fecha_nacimiento'], 'required'],
            [['nombre_completo'], 'string'],
            [['libros_escritos'], 'safe'], // Permitir arrays sin validación estricta de ObjectId
        ];
    }
    // Convertir IDs a ObjectId antes de guardar
    public function beforeSave($insert)
    {
        if ($insert && empty($this->libros_escritos)) {
            $this->libros_escritos = []; // Inicializa siempre como array vacío
        } else if (!empty($this->libros_escritos) && is_array($this->libros_escritos)) {            
            $this->libros_escritos = array_map(fn($id) => new ObjectId($id), $this->libros_escritos); 
        }

        return parent::beforeSave($insert);
    }
    // Relación con la colección de libros
    public function getLibros()
    {
        return $this->hasMany(Libro::class, ['_id' => 'libros_escritos']);
    }
}
