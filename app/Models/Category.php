<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'thumbnail',
        'parent_id'
    ];

    //Inclure par défaut la catégorie parente
    protected $with = ['parent'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

     /**
     * Relation vers la catégorie parente.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function thumbnail() : Attribute{
        return new Attribute(
            fn($value) => str_starts_with($value, 'http') ? $value : asset('storage/'.$value),
            fn($value) => $value
        );
    }

    /**
     * Relation vers les catégories enfants.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Vérifie si la catégorie courante est un descendant de la catégorie passée en paramètre.
     *
     * @param  Category  $category
     * @return bool
     */
    public function isDescendantOf(Category $category): bool
    {
        $parent = $this->parent;
        while ($parent) {
            if ($parent->id === $category->id) {
                return true;
            }
            $parent = $parent->parent;
        }
        return false;
    }

    /**
     * Avant de sauvegarder une catégorie, on ajuste la hiérarchie pour éviter la création d’un cycle.
     */
    protected static function booted()
    {
        static::saving(function (Category $category) {
            // Si une nouvelle catégorie parente est assignée
            if ($category->parent_id) {
                $newParent = Category::find($category->parent_id);

                // Si le nouveau parent est un descendant de la catégorie, un cycle se formerait.
                if ($newParent && $newParent->isDescendantOf($category)) {
                    // On parcourt la chaîne ascendante à partir de $newParent pour trouver
                    // le premier maillon dont le parent est la catégorie en cours.
                    $offending = $newParent;
                    while ($offending && $offending->parent_id != $category->id) {
                        $offending = $offending->parent;
                    }
                    if ($offending) {
                        // Pour éviter le cycle, on détache ce lien en mettant le parent de ce maillon à null.
                        // On utilise withoutEvents pour éviter d'éventuelles récursions lors de la sauvegarde.
                        Category::withoutEvents(function() use ($offending) {
                            $offending->update(['parent_id' => null]);
                        });
                    }
                }
            }
        });
    }

}
