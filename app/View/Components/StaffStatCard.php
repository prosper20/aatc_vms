<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StaffStatCard extends Component
{
    public $title;
    public $value;
    public $percentage;
    public $icon;
    public $bgColor;
    public $textColor;
    public $iconColor;
    public $percentageType;
    public $appendPercentageSymbol;

    /**
     * Create a new component instance.
     *
     * @param  string  $title
     * @param  string|int  $value
     * @param  string  $percentage
     * @param  string  $icon
     * @param  string  $bgColor
     * @param  string  $textColor
     * @param  string  $iconColor
     * @param  string  $percentageType - 'normal', 'inverse', or 'neutral'
     * @return void
     */
    public function __construct($title, $value, $percentage, $icon, $bgColor = 'bg-white', $textColor = 'text-gray-900', $iconColor = 'text-gray-600', $percentageType = 'normal', $appendPercentageSymbol = false)
    {
        $this->title = $title;
        $this->value = $value;
        $this->percentage = $percentage;
        $this->icon = $icon;
        $this->bgColor = $bgColor;
        $this->textColor = $textColor;
        $this->iconColor = $iconColor;
        $this->percentageType = $percentageType;
        $this->appendPercentageSymbol = $appendPercentageSymbol;
    }

    /**
     * Get the percentage color class based on the value and type
     *
     * @return string
     */
    public function getPercentageColorClass()
    {
        $hasPlus = strpos($this->percentage, '+') !== false;
        $hasMinus = strpos($this->percentage, '-') !== false;

        switch ($this->percentageType) {
            case 'inverse':
                // For cases like "Cancelled/Denied" where decrease is good
                if ($hasMinus) {
                    return 'text-green-600';
                } elseif ($hasPlus) {
                    return 'text-red-500';
                } else {
                    return 'text-gray-500';
                }
                break;

            case 'neutral':
                // Always gray regardless of value
                return 'text-gray-500';
                break;

                case 'ratio':
                    // For ratio percentages - color based on value ranges
                    $numericValue = (float) str_replace(['%', '+', '-'], '', $this->percentage);

                    if ($numericValue >= 70) {
                        return 'text-green-700';
                    } elseif ($numericValue >= 40) {
                        return 'text-gray-700';
                    } else {
                        return 'text-red-600';
                    }
                    break;

                case 'ratio-inverse':
                    // For ratio percentages where LOW is GOOD (like denied percentage)
                    $numericValue = (float) str_replace(['%', '+', '-'], '', $this->percentage);

                    if ($numericValue <= 10) {
                        return 'text-green-600';  // Low percentage - good
                    } elseif ($numericValue <= 25) {
                        return 'text-gray-700'; // Medium percentage - neutral
                    } else {
                        return 'text-red-600';    // High percentage - concerning
                    }
                    break;

                case 'ratio-neutral':
                    // For ratio percentages that are just informational (like pending)
                    $numericValue = (float) str_replace(['%', '+', '-'], '', $this->percentage);

                    if ($numericValue <= 15) {
                        return 'text-green-700';  // Low pending - good
                    } elseif ($numericValue <= 30) {
                        return 'text-gray-700'; // Medium pending - neutral
                    } else {
                        return 'text-orange-600'; // High pending - needs attention
                    }
                    break;

            case 'normal':
            default:
                // Normal case where increase is good
                if ($hasPlus) {
                    return 'text-green-600';
                } elseif ($hasMinus) {
                    return 'text-red-600';
                } else {
                    return 'text-gray-500';
                }
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.staff-stat-card');
    }
}
