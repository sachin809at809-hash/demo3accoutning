<?php

namespace App\Http\Middleware;

use App\Traits\Companies;
use App\Traits\Modules;
use App\Traits\Users;
use Closure;
use Illuminate\Auth\AuthenticationException;

class IdentifyCompany
{
    use Companies, Modules, Users;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->request = $request;

        $company_id = $this->getCompanyId();

        // Fallback to default company for the public storefront and webhooks so visitors can access them
        if (empty($company_id) && ($request->is('store*') || $request->is('omnichat*'))) {
            $company_id = 1;
        }

        if (empty($company_id)) {
            abort(500, 'Missing company');
        }

        // Check if user can access company
        if ($this->request->isNotSigned($company_id) && $this->isNotUserCompany($company_id) && !$this->request->is('store*') && !$this->request->is('omnichat*')) {
            throw new AuthenticationException('Unauthenticated.', $guards);
        }

        // Set company as current
        $company = company($company_id);

        if (empty($company)) {
            abort(500, 'Company not found');
        }

        $company->makeCurrent();

        // Load company-specific modules (event listeners, providers)
        $this->registerModules();

        // Fix file/folder paths
        config(['filesystems.disks.' . config('filesystems.default') . '.url' => url('/' . $company_id)  . '/uploads']);

        // Fix routes
        if ($this->request->isNotApi()) {
            app('url')->defaults(['company_id' => $company_id]);
            $this->request->route()->forgetParameter('company_id');
        }

        return $next($this->request);
    }
}
