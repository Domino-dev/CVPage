<?php

namespace App\Service;

use App\Entity\Project;
use Imagine\Gd\Imagine as GdImagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectService
{
    public function createImages(Project $project, ?array $mImages = [], ?UploadedFile $sImage, string $imagesUploadDir): void
    {
        if (!empty($mImages) || !empty($sImage)) {
            $imagine = new GdImagine();
            
            if(!empty($sImage) && $sImage instanceof UploadedFile){
                $sImageName = 's_'.uniqid().'.jpg';
                $sImageImagine = $imagine->open($sImage->getPathname());
                $sImageImagine->thumbnail(new Box(1200, 800), ImageInterface::THUMBNAIL_INSET)
                ->save($imagesUploadDir.'/'.$sImageName,[
                        'jpeg_quality' => 100,
                        'jpeg_progressive' => true
                    ]);
                $project->setSImage($sImageName);
            }

            if(!empty($mImages)){

                usort($mImages, function(UploadedFile $a, UploadedFile $b) {
                    return strcmp($a->getClientOriginalName(), $b->getClientOriginalName());
                });

                $mImageNames = [];
                foreach($mImages as $mImage){
                    $mImageNames[] = $mImageName = 'm_'.uniqid().'.jpg';
                    $mImageImagine = $imagine->open($mImage->getPathname());
                    $mImageImagine->thumbnail(new Box(1200, 800), ImageInterface::THUMBNAIL_INSET)
                    ->save($imagesUploadDir.'/'.$mImageName,[
                        'jpeg_quality' => 100,
                        'jpeg_progressive' => true
                    ]);
                }

                $project->setMImage(implode(';', $mImageNames));
            } 
        }
    }

    public function deleteImages(string $mImages, string $sImage, string $imagesUplaodDir){
        $mImages = !empty($mImages) ? explode(';',$mImages) : [];

        foreach($mImages as $mImage){

            if(!file_exists($imagesUplaodDir.'/'.$mImage)){
                continue;
            }

            unlink($imagesUplaodDir.'/'.$mImage);
        }

        if(!empty($sImage)){
            unlink($imagesUplaodDir.'/'.$sImage);
        }
    }
}