import { Controller, Post, Body, Inject } from '@nestjs/common';
import { AppService } from './app.service';
import { RegisterDto } from './dto/register.dto';
import { ClientProxy } from '@nestjs/microservices';

@Controller('auth')
export class AppController {
  constructor(
    private readonly appService: AppService,
    @Inject('USER_SERVICE') private readonly client: ClientProxy
  ) {}

  @Post('register')
  async register(@Body() registerDto: RegisterDto) {
    const user = { userId: Math.floor(Math.random() * 1000), email: registerDto.email };
    
    // Emit event to RabbitMQ as required by UAS
    this.client.emit('user.created', user);
    
    return {
      message: 'User registered successfully',
      data: user
    };
  }

  @Post('login')
  async login(@Body() loginDto: any) {
    return {
      message: 'Login successful',
      token: 'mock-jwt-token'
    };
  }
}
