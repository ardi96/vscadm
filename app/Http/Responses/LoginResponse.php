<?php

namespace App\Http\Responses;
 
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
 
class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // Here, you can define which resource and which page you want to redirect to
        if (Auth::user()->can('view finance dashboard')) {
            return redirect()->to(\App\Filament\Pages\FinanceDashboard::getUrl());
        }   
        else
        {
            return redirect()->to($this->getFirstAccessiblePage());
        }
    }

    private function getFirstAccessiblePage(): string
    {

        $user = Auth::user();
    
        // Get all registered Filament pages & resources
        $panels = Filament::getCurrentPanel();

        $pages = $panels->getPages();
    
        foreach ($pages as $pageClass => $page) {
            if (method_exists($page, 'canAccess')) {
                if ($page::canAccess($user)) {
                    return $page::getUrl();
                }
            }
            else {
                return $page::getUrl();
            }
        }
    
        return route('filament.auth.logout'); // Fallback if no pages are accessible
    }
}