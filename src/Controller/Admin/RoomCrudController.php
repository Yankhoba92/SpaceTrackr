<?php

// src/Controller/Admin/RoomCrudController.php
namespace App\Controller\Admin;

use App\Entity\Room;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            // AssociationField::new('user')
            //                 ->formatValue(fn ($value) => $value->getUserName())
            //                 ->autocomplete(),
            TextField::new('name'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('city'),
            IntegerField::new('zipCode'),
            TextField::new('address'),
            IntegerField::new('capacity'),
            ImageField::new('roomPicture')->hideOnIndex()
                ->setBasePath('uploads/rooms/')
                ->setUploadDir('public/uploads/rooms/'),
            
        ];
    }
}
