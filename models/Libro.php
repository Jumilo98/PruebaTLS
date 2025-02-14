<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use MongoDB\BSON\ObjectId;

class Libro extends ActiveRecord
{
    public static function collectionName()
    {
        // Nombre de la colección en MongoDB
        return 'libros';
    }

    public function attributes()
    {
        // Atributos de la colección
        return ['_id', 'titulo', 'autor_ids', 'anio_publicacion', 'descripcion'];
    }

    public function rules()
    {
        // Reglas de validación
        return [
            [['titulo', 'autor_ids', 'anio_publicacion'], 'required'],
            [['titulo', 'descripcion'], 'string'],
            [['anio_publicacion'], 'integer'],
            [['autor_ids'], 'safe'], // Permitir arrays sin validación estricta de ObjectId
        ];
    }
    // Convertir IDs a ObjectId antes de guardar
    public function beforeSave($insert)
    {
        if (!empty($this->autor_ids) && is_array($this->autor_ids)) {
            $this->autor_ids = array_map(fn($id) => new ObjectId($id), $this->autor_ids);
        }

        return parent::beforeSave($insert);
    }
    // Agregar el libro al autor automáticamente después de guardarlo
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && !empty($this->autor_ids)) {
            // Recorrer los IDs de autores
            foreach ($this->autor_ids as $autorId) {
                // Buscar el autor por ID
                $autor = Autor::findOne(['_id' => $autorId]);
                // Si el autor existe
                if ($autor) {
                    // Obtener la lista de libros actuales del autor
                    $librosExistentes = is_array($autor->libros_escritos) ? $autor->libros_escritos : [];
                    // Evitar duplicados antes de agregar el nuevo libro
                    if (!in_array((string)$this->_id, array_map('strval', $librosExistentes))) {
                        $librosExistentes[] = new ObjectId($this->_id);
                        $autor->updateAttributes(['libros_escritos' => $librosExistentes]);
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
    // Relación con la colección de autores
    public function getAutores()
    {
        return $this->hasMany(Autor::class, ['_id' => 'autor_ids']);
    }
}
