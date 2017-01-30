<?php namespace DesignPond\Registry\Timestamps;

interface TimestampInterface {

    /**
     * Check for expired
     *
     * @param  string $cached_at
     * @return bool
     */

    public function check($cached_at);

    /**
     * Update.
     *
     * @param  string $cached_at
     * @return bool
     */
    
    public function update($cached_at);
}