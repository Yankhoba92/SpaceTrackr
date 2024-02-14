<?php

namespace App\Service;


class ProfileService
{
    public function updateProfile($form, $user, $em, $target)
    {
        $user->setUsername($form->get('username')->getData());

        // Upload image
        if($form->get('userPicture')->getData()) {
            $file = $form->get('userPicture')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($target, $filename);
            $user->setUserPicture($filename);
        } else {
            if($user->getUserPicture() == null) {
                $user->setUserPicture('default.png');
            } else {
            $user->setUserPicture($user->getImage());
            }
        }

        $em->persist($user);
        $em->flush();
    }
}