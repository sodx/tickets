<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\SchemaOrg\Schema;

class GenerateEventSchema
{
    use AsAction;

    public function handle($event)
    {
        if($event->segment === 'music') {
            $eventSchema = $this->generateMusicEventSchema($event);
        } elseif ($event->segment === 'sports') {
            $eventSchema = $this->generateSportsEventSchema($event);
        } else {
            $eventSchema = $this->generateEventSchema($event);
        }
        return [
            'event' => $eventSchema,
            'faq' => $this->generateFAQForEventSchema($event)
        ];
    }

    public function generateFAQForEventSchema($event)
    {
        $faqs = [];
        $faqsArr = [];
        if (!empty($event->price_min) && $event->price_min > 0) {
            $faqsArr[] = (object) [
                'question' => 'What is the min. price of the event?',
                'answer' => 'The price of the event is $' . $event->price_min . '.'
            ];
        }

        if (!empty($event->start_date)) {
            $faqsArr[] = (object) [
                'question' => 'When is the event?',
                'answer' => 'The event is on ' . $event->getFormattedDateTimeAttribute() . '.'
            ];
        }

        if (!empty($event->venue->address)) {
            $faqsArr[] = (object) [
                'question' => 'Where is the event?',
                'answer' => 'The event is at ' . $event->venue->address . ', ' . $event->venue->city . ', ' . $event->venue->state . '.'
            ];
        }

        foreach ($faqsArr as $faq) {
            $faqs[] = Schema::question()
                ->name($faq->question)
                ->acceptedAnswer(
                    Schema::answer()
                    ->text($faq->answer)
                )
            ->toScript();
        }
        return implode('', $faqs);
    }

    public function generateEventSchema($event): string
    {
        return Schema::event()
            ->image(isset($event->thumbnail) ? $event->thumbnail : '')
            ->name($event->name)
            ->description($event->info)
            ->startDate($event->start_date)
            ->endDate($event->end_date ?? $event->start_date)
            ->eventStatus('https://schema.org/EventScheduled')
            ->eventAttendanceMode('https://schema.org/OfflineEventAttendanceMode')
            ->location(
                Schema::place()
                ->name($event->venue->name)
                ->address(
                    Schema::postalAddress()
                    ->streetAddress($event->venue->address)
                    ->addressLocality($event->venue->city)
                    ->addressRegion($event->venue->state)
                    ->postalCode($event->venue->zip)
                    ->addressCountry($event->venue->country)
                )
                ->geo(
                    Schema::geoCoordinates()
                    ->latitude($event->venue->latitude)
                    ->longitude($event->venue->longtitude)
                )
            )
            ->offers(
                Schema::offer()
                ->price($event->price_min)
                ->priceCurrency($event->price_currency)
                ->availability("https://schema.org/InStock")
                ->url($event->url)
            )
            ->organizer(
                Schema::organization()
                ->name($event->venue->name)
                ->url(
                    route('venue', ['slug' => $event->venue->slug])
                )
            )
            ->performer($this->getPerformersSchema($event))
            ->url(
                route('event', [
                    'slug' => $event->slug,
                    'segment' => $event->segment->slug,
                    'location' => Slugify::run($event->venue->city)
                ])
            )
            ->toScript();
    }

    public function generateMusicEventSchema($event): string
    {
        return Schema::musicEvent()
            ->image(isset($event->thumbnail) ? $event->thumbnail : '')
            ->name($event->name)
            ->description($event->info)
            ->startDate($event->start_date)
            ->endDate($event->end_date ?? $event->start_date)
            ->eventStatus('https://schema.org/EventScheduled')
            ->eventAttendanceMode('https://schema.org/OfflineEventAttendanceMode')
            ->location(
                Schema::place()
                    ->name($event->venue->name)
                    ->address(
                        Schema::postalAddress()
                            ->streetAddress($event->venue->address)
                            ->addressLocality($event->venue->city)
                            ->addressRegion($event->venue->state)
                            ->postalCode($event->venue->zip)
                            ->addressCountry($event->venue->country)
                    )
                    ->geo(
                        Schema::geoCoordinates()
                            ->latitude($event->venue->latitude)
                            ->longitude($event->venue->longtitude)
                    )
            )
            ->offers(
                Schema::offer()
                    ->price($event->price_min)
                    ->priceCurrency($event->price_currency)
                    ->availability("https://schema.org/InStock")
                    ->url($event->url)
            )
            ->organizer(
                Schema::organization()
                    ->name($event->venue->name)
                    ->url(
                        route('venue', ['slug' => $event->venue->slug])
                    )
            )
            ->performer($this->getPerformersSchema($event))
            ->url(
                route('event', [
                    'slug' => $event->slug,
                    'segment' => $event->segment->slug,
                    'location' => Slugify::run($event->venue->city)
                ])
            )
            ->toScript();
    }

    public function generateSportsEventSchema($event): string
    {
        return Schema::sportsEvent()
            ->image(isset($event->thumbnail) ? $event->thumbnail : '')
            ->name($event->name)
            ->description($event->info)
            ->startDate($event->start_date)
            ->endDate($event->end_date ?? $event->start_date)
            ->eventStatus('https://schema.org/EventScheduled')
            ->eventAttendanceMode('https://schema.org/OfflineEventAttendanceMode')
            ->location(
                Schema::place()
                    ->name($event->venue->name)
                    ->address(
                        Schema::postalAddress()
                            ->streetAddress($event->venue->address)
                            ->addressLocality($event->venue->city)
                            ->addressRegion($event->venue->state)
                            ->postalCode($event->venue->zip)
                            ->addressCountry($event->venue->country)
                    )
                    ->geo(
                        Schema::geoCoordinates()
                            ->latitude($event->venue->latitude)
                            ->longitude($event->venue->longtitude)
                    )
            )
            ->offers(
                Schema::offer()
                    ->price($event->price_min)
                    ->priceCurrency($event->price_currency)
                    ->availability("https://schema.org/InStock")
                    ->url($event->url)
            )
            ->organizer(
                Schema::organization()
                    ->name($event->venue->name)
                    ->url(
                        route('venue', ['slug' => $event->venue->slug])
                    )
            )
            ->performer($this->getPerformersSchema($event))
            ->url(
                route('event', [
                    'slug' => $event->slug,
                    'segment' => $event->segment->slug,
                    'location' => Slugify::run($event->venue->city)
                ])
            )
            ->toScript();
    }


    public function getPerformersSchema($event)
    {
        $performers = [];
        foreach ($event->attractions as $performer) {
            $performers[] = Schema::performingGroup()
                ->name($performer->name)->toArray();
        }
        return $performers;
    }
}
