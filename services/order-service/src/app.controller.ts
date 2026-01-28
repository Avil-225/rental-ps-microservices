import { Controller, Post, Body, Get } from '@nestjs/common';
import { AppService } from './app.service';
import { CreateOrderDto } from './dto/create-order.dto';
import { EventPattern, MessagePattern } from '@nestjs/microservices';

@Controller('orders')
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Post('create')
  async createOrder(@Body() createOrderDto: CreateOrderDto) {
    // In a real app, this would save to the database.
    // For the UAS, we focus on communication and architecture.
    return {
      message: 'Order created successfully',
      data: {
        orderId: Math.floor(Math.random() * 10000),
        ...createOrderDto
      }
    };
  }

  // This listens for the 'user.created' event from RabbitMQ
  @EventPattern('user.created')
  async handleUserCreated(data: any) {
    console.log('Order Service received user.created event:', data);
    // Here you could add logic like creating a customer profile in the order database
  }

  // Example of a message pattern for synchronous communication if needed
  @MessagePattern({ cmd: 'get_orders' })
  async getOrders(data: any) {
    // Mock data for demonstration
    return [{ id: 1, item: 'PS5 Rental', status: 'Active' }];
  }
}
