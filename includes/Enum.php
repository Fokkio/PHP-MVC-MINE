<?php
    enum Gender: string {
        case MALE = "male";
        case FEMALE = "female";
        case OTHER = "other";
        public static function fromValue(string $value): self
        {
            return match ($value) {
                'male' => self::MALE,           
                'female' => self::FEMALE,
                'other' => self::OTHER, 
                default => throw new InvalidArgumentException("Invalid gender value: $value"),
            };      
        }
    }

    enum JoinStatus: string {
        case PENDING = 'pending';
        case APPROVED = 'approved';
        case REJECTED = 'rejected';
    }

