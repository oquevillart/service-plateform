<?php

namespace App\Controller\Admin;

use App\Entity\ShortenedUrl;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShortenedUrlCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShortenedUrl::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('url'),
            TextField::new('slug'),
        ];
    }
}
